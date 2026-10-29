<?php
require_once '../models/Database.php';
require_once '../models/Games.php';

$gamesModel = new Games();
$erros = [];
$idEdicao = null;
$jogo = [
    'nome' => '',
    'produtora' => '',
    'genero' => '',
    'quantidade' => '',
    'preco' => '',
];

if (isset($_GET['id'])) {
    $idEdicao = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($idEdicao === false || $idEdicao < 1) {
        http_response_code(400);
        exit('ID de jogo inválido.');
    }

    $jogoEncontrado = $gamesModel->getGameById($idEdicao);
    if (!$jogoEncontrado) {
        http_response_code(404);
        exit('Jogo não encontrado.');
    }

    $jogo = $jogoEncontrado;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    $idPostado = $_POST['id'] ?? '';

    if ($acao === 'excluir') {
        $idExcluir = filter_var($idPostado, FILTER_VALIDATE_INT);
        if ($idExcluir === false || $idExcluir < 1) {
            http_response_code(400);
            exit('ID de jogo inválido.');
        }

        if (!$gamesModel->getGameById($idExcluir)) {
            http_response_code(404);
            exit('Jogo não encontrado.');
        }

        $gamesModel->deleteGame($idExcluir);
        header('Location: index.php?sucesso=excluido');
        exit;
    }

    if ($acao === 'salvar') {
        $nome = trim($_POST['nome'] ?? '');
        $produtora = trim($_POST['produtora'] ?? '');
        $genero = trim($_POST['genero'] ?? '');
        $quantidade = filter_var($_POST['quantidade'] ?? '', FILTER_VALIDATE_INT);
        $precoInformado = $_POST['preco'] ?? '';
        $preco = is_numeric($precoInformado) ? (float) $precoInformado : 0;

        $jogo = [
            'nome' => $nome,
            'produtora' => $produtora,
            'genero' => $genero,
            'quantidade' => $_POST['quantidade'] ?? '',
            'preco' => $precoInformado,
        ];

        if ($nome === '' || strlen($nome) > 100) {
            $erros[] = 'Informe um nome com até 100 caracteres.';
        }
        if ($produtora === '' || strlen($produtora) > 100) {
            $erros[] = 'Informe uma produtora com até 100 caracteres.';
        }
        if ($genero === '' || strlen($genero) > 100) {
            $erros[] = 'Informe um gênero com até 100 caracteres.';
        }
        if ($quantidade === false || $quantidade < 0) {
            $erros[] = 'A quantidade deve ser um número inteiro igual ou maior que zero.';
        }
        if (!is_numeric($precoInformado) || $preco <= 0) {
            $erros[] = 'O preço deve ser maior que zero.';
        }

        if ($idPostado !== '') {
            $idEdicao = filter_var($idPostado, FILTER_VALIDATE_INT);
            if ($idEdicao === false || $idEdicao < 1) {
                $erros[] = 'ID de jogo inválido.';
                $idEdicao = null;
            } elseif (!$gamesModel->getGameById($idEdicao)) {
                http_response_code(404);
                exit('Jogo não encontrado.');
            }
        }

        if ($erros === []) {
            if ($idEdicao === null) {
                $gamesModel->addGames($nome, $produtora, $genero, $quantidade, $preco);
            } else {
                $gamesModel->updateGame($idEdicao, $nome, $produtora, $genero, $quantidade, $preco);
            }

            header('Location: index.php?sucesso=salvo');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $idEdicao === null ? 'Cadastrar jogo' : 'Editar jogo' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5">
        <div class="mx-auto" style="max-width: 640px;">
            <header class="mb-4">
                <p class="text-secondary text-uppercase fw-semibold small mb-1">Catálogo</p>
                <h1><?= $idEdicao === null ? 'Cadastrar jogo' : 'Editar jogo' ?></h1>
            </header>

            <?php if ($erros !== []): ?>
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        <?php foreach ($erros as $erro): ?>
                            <li><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <section class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="gerenciar.php<?= $idEdicao !== null ? '?id=' . (int) $idEdicao : '' ?>" method="POST">
                        <input type="hidden" name="acao" value="salvar">
                        <?php if ($idEdicao !== null): ?>
                            <input type="hidden" name="id" value="<?= (int) $idEdicao ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do jogo:</label>
                            <input type="text" class="form-control" id="nome" name="nome" maxlength="100" value="<?= htmlspecialchars((string) $jogo['nome'], ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="produtora" class="form-label">Produtora:</label>
                            <input type="text" class="form-control" id="produtora" name="produtora" maxlength="100" value="<?= htmlspecialchars((string) $jogo['produtora'], ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="genero" class="form-label">Gênero:</label>
                            <input type="text" class="form-control" id="genero" name="genero" maxlength="100" value="<?= htmlspecialchars((string) $jogo['genero'], ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="quantidade" class="form-label">Quantidade:</label>
                            <input type="number" class="form-control" id="quantidade" name="quantidade" min="0" step="1" value="<?= htmlspecialchars((string) $jogo['quantidade'], ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>

                        <div class="mb-4">
                            <label for="preco" class="form-label">Preço:</label>
                            <input type="number" class="form-control" id="preco" name="preco" min="0.01" step="0.01" value="<?= htmlspecialchars((string) $jogo['preco'], ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><?= $idEdicao === null ? 'Cadastrar' : 'Salvar alterações' ?></button>
                            <a href="index.php" class="btn btn-outline-secondary">Voltar para a lista</a>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
