<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'admin') {
    header('Location: ../Comuns/erro.php');
    exit();
}
require_once '../../BLL/Admin/BLL_permissoes.php';
$permBLL = new AdminPermissoesManager();

// Mapear permissões reais por ficheiro/menu de cada UI
$permissoes = $permBLL->getAllPermissoes();
$perfis = [];
$colunas = [];
$allPermIds = [];
foreach ($permissoes as $p) {
    $perfis[$p['perfil_id']]['nome'] = $p['perfil'];
    $perfis[$p['perfil_id']]['permissoes'][$p['permissao']] = [
        'id' => $p['id'],
        'valor' => $p['valor']
    ];
    $colunas[$p['permissao']] = true;
    $allPermIds[] = $p['id'];
}

// Ordem dos menus reais por UI (ajuste conforme necessário)
$colunas_ordem = [
    // Admin UI
    'pagina_inicial_admin',
    'utilizadores',
    'permissoes',
    'campos_personalizados',
    'alertas',
    // RH UI
    'pagina_inicial_rh',
    'colaboradores_gerir',
    'equipas',
    'relatorios',
    'exportar',
    'gerir_beneficios',
    'gerir_formacoes',
    'gerir_recibos',
    'campos_personalizados_rh',
    // Coordenador UI
    'pagina_inicial_coordenador',
    'equipa',
    'relatorios_equipa',
    // Colaborador UI
    'ficha_colaborador',
    'beneficios',
    'ferias',
    'formacoes',
    'recibos',
    // Comuns
    'notificacoes',
    'perfil',
    'logout',
    // Convidado
    'onboarding_convidado'
];
$colunas = array_values(array_intersect($colunas_ordem, array_keys($colunas)));

// Atualizar permissões
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['permissao'])) {
    foreach ($_POST['permissao'] as $id => $valor) {
        $permBLL->updatePermissao($id, $valor ? 1 : 0);
    }
    foreach ($allPermIds as $id) {
        if (!isset($_POST['permissao'][$id])) {
            $permBLL->updatePermissao($id, 0);
        }
    }
    header('Location: permissoes.php');
    exit();
}

// Mapeamento organizado por categorias
$categorias = [
    '🔐 Autenticação & Conta' => [
        'alterar_password' => 'Alterar palavra-passe pessoal'
    ],
    '👤 Ficha do colaborador' => [
        'ver_propria_ficha' => 'Ver a sua própria ficha',
        'editar_propria_ficha' => 'Editar a sua ficha (via pedidos)',
        'ver_ficha_equipa' => 'Ver ficha dos membros da sua equipa',
        'ver_todas_fichas' => 'Ver ficha de todos os colaboradores',
        'editar_outras_fichas' => 'Editar ficha de outros colaboradores',
        'remover_colaboradores' => 'Remover colaboradores',
        'criar_colaboradores' => 'Criar novos colaboradores (onboarding)',
        'exportar_colaboradores' => 'Exportar lista de colaboradores'
    ],
    '📁 Campos personalizados' => [
        'ver_campos_personalizados' => 'Ver campos personalizados',
        'gerir_campos_personalizados' => 'Adicionar/editar campos personalizados'
    ],
    '📤 Pedidos & Comprovativos' => [
        'fazer_pedidos_alteracao' => 'Fazer pedidos de alteração de dados',
        'enviar_comprovativos' => 'Enviar comprovativos (CC, IBAN, morada, etc.)',
        'aprovar_pedidos' => 'Aprovar/recusar pedidos e comprovativos'
    ],
    '📅 Férias' => [
        'pedir_ferias' => 'Fazer pedido de férias',
        'aprovar_ferias' => 'Aprovar/recusar férias',
        'ver_ferias_equipa' => 'Ver pedidos de férias da equipa'
    ],
    '💡 Alertas & Notificações' => [
        'receber_alertas' => 'Receber alertas/notificações',
        'gerir_alertas_sistema' => 'Gerir alertas (criar/atribuir/editar)'
    ],
    '🧑‍🤝‍🧑 Equipas' => [
        'ver_proprias_equipas' => 'Ver equipas onde está inserido',
        'ver_composicao_equipa' => 'Ver composição da sua equipa',
        'gerir_equipas' => 'Criar/editar equipas',
        'remover_equipas' => 'Remover equipas'
    ],
    '📈 Relatórios e Dashboards' => [
        'ver_dashboard_equipa' => 'Ver dashboard da sua equipa',
        'ver_relatorios_aniversarios' => 'Ver relatórios de aniversários',
        'ver_relatorios_contratuais' => 'Ver relatórios de alterações contratuais',
        'ver_relatorios_vouchers' => 'Ver relatórios de vouchers',
        'exportar_relatorios' => 'Exportar relatórios'
    ],
    '🎓 Formações' => [
        'ver_formacoes' => 'Ver formações disponíveis',
        'inscrever_formacoes' => 'Inscrever-se em formações',
        'gerir_formacoes' => 'Gerir e adicionar formações'
    ],
    '🧾 Benefícios' => [
        'ver_beneficios' => 'Ver benefícios disponíveis',
        'gerir_beneficios' => 'Gerir benefícios (criar, editar, remover)'
    ],
    '📑 Recibos de Vencimento' => [
        'ver_recibos_proprios' => 'Ver recibos próprios',
        'atribuir_recibos' => 'Atribuir recibos de vencimento aos colaboradores'
    ],
    '🔧 Administração do Sistema' => [
        'ver_utilizadores' => 'Ver lista de utilizadores (ativos/inativos)',
        'gerir_permissoes' => 'Gerir permissões',
        'gerir_perfis' => 'Gerir perfis',
        'acesso_dados_sistema' => 'Acesso a dados de colaboradores'
    ]
];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Permissões - Portal Tlantic</title>
    <link rel="stylesheet" href="../../assets/CSS/Admin/base.css">
    <link rel="stylesheet" href="../../assets/CSS/Comuns/header.css">
    <style>
        .permissoes-container {
            max-width: 1100px;
            margin: 36px auto 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(3,96,233,0.08);
            padding: 32px 32px 36px 32px;
        }
        .permissoes-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .permissoes-header h1 {
            font-size: 2rem;
            color: #0360e9;
            margin: 0;
        }
        .tabela-scroll {
            overflow-x: auto;
        }
        .tabela-permissoes {
            width: 100%;
            min-width: 700px;
            border-collapse: separate;
            border-spacing: 0;
            background: #f5f7fa;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(3,96,233,0.04);
        }
        .tabela-permissoes th, .tabela-permissoes td {
            padding: 13px 14px;
            text-align: center;
        }
        .tabela-permissoes th {
            background: linear-gradient(90deg, #0360e9 0%, #299cf3 100%);
            color: #fff;
            font-weight: 600;
            border-bottom: 2px solid #299cf3;
        }
        .tabela-permissoes tr:nth-child(even) {
            background: #e0eaff;
        }
        .tabela-permissoes tr:hover {
            background: #c3cfe2;
        }
        .tabela-permissoes td {
            color: #1c3c69;
            font-size: 1rem;
        }
        .perfil-col {
            font-weight: 600;
            color: #0360e9;
            text-align: left;
        }
        .btn-salvar {
            background: linear-gradient(135deg, #0360e9 0%, #299cf3 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 10px 28px;
            font-size: 1.08rem;
            cursor: pointer;
            margin-top: 18px;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(3,96,233,0.08);
        }
        .btn-salvar:hover {
            background: linear-gradient(135deg, #1c3c69 0%, #0360e9 100%);
        }
        @media (max-width: 900px) {
            .permissoes-container { padding: 12px 4px; }
            .permissoes-header h1 { font-size: 1.3rem; }
            .tabela-permissoes th, .tabela-permissoes td { padding: 8px 6px; font-size: 0.95rem; }
        }
    </style>
</head>
<body>
    <header>
        <a href="pagina_inicial_admin.php">
            <img src="../../assets/tlantic-logo2.png" alt="Logo Tlantic" class="logo-header">
        </a>        
        <nav>
            <a href="utilizadores.php">Utilizadores</a>
            <a href="permissoes.php">Permissões</a>
            <a href="alertas.php">Alertas</a>
            <a href="../Comuns/perfil.php" class="perfil-link">Perfil</a>
            <a href="../Comuns/logout.php" class="sair-link">Sair</a>
        </nav>
    </header>
    <div class="permissoes-container">
        <div class="permissoes-header">
            <h1>Permissões dos Perfis</h1>
        </div>
        <form method="POST">
        <div class="tabela-scroll">
        <table class="tabela-permissoes">
            <thead>
                <tr>
                    <th style="width: 200px;">Categoria / Permissão</th>
                    <th>Colaborador</th>
                    <th>Coordenador</th>
                    <th>RH</th>
                    <th>Admin</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria => $permissoes_cat): ?>
                    <tr style="background: #e7f3ff;">
                        <td colspan="5" style="font-weight: bold; color: #0360e9; text-align: center; padding: 12px;">
                            <?= htmlspecialchars($categoria) ?>
                        </td>
                    </tr>
                    <?php foreach ($permissoes_cat as $perm_key => $perm_label): ?>
                        <tr>
                            <td style="text-align: left; font-size: 0.9rem; padding-left: 20px;">
                                <?= htmlspecialchars($perm_label) ?>
                            </td>
                            <?php 
                            $perfis_ordem = [2, 3, 4, 5]; // colaborador, coordenador, rh, admin
                            foreach ($perfis_ordem as $perfil_id): 
                                if (isset($perfis[$perfil_id]['permissoes'][$perm_key])): 
                            ?>
                                <td>
                                    <input type="checkbox" 
                                           name="permissao[<?= $perfis[$perfil_id]['permissoes'][$perm_key]['id'] ?>]" 
                                           value="1" 
                                           <?= $perfis[$perfil_id]['permissoes'][$perm_key]['valor'] ? 'checked' : '' ?>>
                                </td>
                            <?php else: ?>
                                <td><span style="color:#bbb;">—</span></td>
                            <?php endif; 
                            endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <div style="text-align:center;">
            <button type="submit" class="btn-salvar">Guardar Permissões</button>
        </div>
        </form>
    </div>

</body>
</html>