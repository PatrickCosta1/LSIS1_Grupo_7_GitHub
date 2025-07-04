<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profile'], ['rh', 'admin'])) {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/RH/BLL_colaboradores_gerir.php';
$colabBLL = new RHColaboradoresManager();
$colaboradores = $colabBLL->getAllColaboradores($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Colaboradores - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/RH/colaboradores_gerir.css">
</head>
<body>
    <header>
        <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header" style="cursor:pointer;" onclick="window.location.href='pagina_inicial_RH.php';">
        <nav>
            <?php if ($_SESSION['profile'] === 'admin'): ?>
                <a href="../Admin/utilizadores.php">Utilizadores</a>
                <a href="../Admin/permissoes.php">Permissões</a>
                <a href="../Admin/campos_personalizados.php">Campos Personalizados</a>
                <a href="../Admin/alertas.php">Alertas</a>
                <a href="colaboradores_gerir.php">Colaboradores</a>
                <a href="../Comuns/perfil.php">Perfil</a>
                <a href="../Comuns/logout.php">Sair</a>
            <?php else: ?>
            <div class="dropdown-equipas">
                <a href="equipas.php" class="equipas-link">
                    Equipas
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="relatorios.php">Relatórios</a>
                    <a href="dashboard_rh.php">Dashboard</a>
                </div>
            </div>
            <div class="dropdown-colaboradores">
                <a href="colaboradores_gerir.php" class="colaboradores-link">
                    Colaboradores
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="exportar.php">Exportar</a>
                </div>
            </div>
            <div class="dropdown-gestao">
                <a href="#" class="gestao-link">
                    Gestão
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="gerir_beneficios.php">Gerir Benefícios</a>
                    <a href="gerir_formacoes.php">Gerir Formações</a>
                </div>
            </div>
            <a href="../Comuns/notificacoes.php">Notificações</a>
            <div class="dropdown-perfil">
                <a href="../Comuns/perfil.php" class="perfil-link">
                    Perfil
                    <span class="seta-baixo">&#9662;</span>
                </a>
                <div class="dropdown-menu">
                    <a href="../Colaborador/ficha_colaborador.php">Perfil Colaborador</a>
                </div>
            </div>
            <a href="../Comuns/logout.php">Sair</a>

            <?php endif; ?>
        </nav>
    </header>
    <div class="portal-brand">
        <div class="color-bar">
            <div class="color-segment"></div>
            <div class="color-segment"></div>
            <div class="color-segment"></div>
        </div>
        <span class="portal-text">Portal Do Colaborador</span>
    </div>
    <main>
        <h1>Gestão de Colaboradores</h1>
        <div class="tabela-colaboradores-wrapper">
            <table class="tabela-colaboradores tabela-colaboradores-compacta">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Equipas</th>
                        <th style="min-width:90px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($colaboradores as $col): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($col['nome']); ?></td>
                        <td><?php echo htmlspecialchars($col['username'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($col['email']); ?></td>
                        <td>
                            <?php
                            if (isset($col['perfil'])) {
                                $tipo = strtolower($col['perfil']);
                                if ($tipo === 'coordenador') {
                                    echo 'Coordenador';
                                } elseif ($tipo === 'colaborador') {
                                    echo 'Colaborador';
                                } elseif ($tipo === 'rh') {
                                    echo 'RH';
                                } elseif ($tipo === 'admin') {
                                    echo 'Administrador';
                                } else {
                                    echo ucfirst($tipo);
                                }
                            } else {
                                echo 'Colaborador';
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($col['equipas']); ?></td>
                        <td>
                            <a href="../Colaborador/ficha_colaborador.php?id=<?php echo $col['id']; ?>" class="btn btn-sm">Ver</a>
                            <a href="#" class="btn btn-danger btn-sm">Remover</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <a href="colaborador_novo.php" class="btn add-colab-btn">Adicionar Novo Colaborador</a>
    </main>
</body>
</html>