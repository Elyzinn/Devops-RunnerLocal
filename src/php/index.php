<?php
require_once '../models/Database.php';
require_once '../models/Games.php';

$games = (new Games())->listGame();
$mensagens = [
    'salvo' => 'Jogo salvo com sucesso.',
    'excluido' => 'Jogo excluído com sucesso.',
];
$mensagem = $mensagens[$_GET['sucesso'] ?? ''] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogos | Catálogo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5">
        <header class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <p class="text-secondary text-uppercase fw-semibold small mb-1">Catálogo</p>
                <h1 class="mb-0">Jogos</h1>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-secondary"><?= count($games) ?> <?= count($games) === 1 ? 'jogo cadastrado' : 'jogos cadastrados' ?></span>
                <a href="gerenciar.php" class="btn btn-primary">Cadastrar jogo</a>
            </div>
        </header>

        <?php if ($mensagem !== ''): ?>
            <div class="alert alert-success" role="status"><?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <section class="card shadow-sm" aria-label="Lista de jogos">
            <?php if ($games === []): ?>
                <p class="text-secondary text-center p-5 mb-0">Nenhum jogo cadastrado ainda.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-3" scope="col">Jogo</th>
                                <th scope="col">Produtora</th>
                                <th scope="col">Gênero</th>
                                <th scope="col">Quantidade</th>
                                <th scope="col">Preço</th>
                                <th class="text-end px-3" scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($games as $game): ?>
                                <tr>
                                    <td class="px-3 fw-semibold"><?= htmlspecialchars($game['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($game['produtora'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($game['genero'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($game['quantidade'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="price">R$ <?= number_format((float) $game['preco'], 2, ',', '.') ?></td>
                                    <td class="text-end px-3">
                                        <div class="d-inline-flex gap-2">
                                            <a href="gerenciar.php?id=<?= (int) $game['id'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                            <form action="gerenciar.php" method="POST" onsubmit="return confirm('Deseja excluir este jogo?');">
                                                <input type="hidden" name="acao" value="excluir">
                                                <input type="hidden" name="id" value="<?= (int) $game['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
