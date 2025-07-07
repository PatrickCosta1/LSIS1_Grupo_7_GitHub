# Commits by author
#### 1230654@isep.ipp.pt
<details>
<summary>Diff</summary>

<pre>
 0 files changed
</pre>
</details>
<details>
<summary>Commits</summary>

<pre>
</pre>

</details>

#### 1230881@isep.ipp.pt
<details>
<summary>Diff</summary>

<pre>
 0 files changed
</pre>
</details>
<details>
<summary>Commits</summary>

<pre>
commit d39069a11f36ec4d3ce6314fc2c0b1a81e906c38	refs/remotes/origin/patrick_branch (origin/patrick_branch)
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Fri Jul 4 18:31:12 2025 +0100

    authenticator

A	vendor/bacon/bacon-qr-code
A	vendor/dasprid/enum
A	vendor/paragonie/constant_time_encoding
A	vendor/pragmarx/google2fa

commit 3adf4abc7cf346afeb1185504c43ac5f40bee2e0	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Fri Jul 4 18:28:00 2025 +0100

    Authenticator funcional

M	BLL/Comuns/BLL_login.php
A	BLL/RH/ajax_onboarding_dados.php
M	DAL/Comuns/DAL_login.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Comuns/login.php
M	UI/Comuns/notificacoes.php
M	UI/Convidado/onboarding_convidado.php
M	UI/Coordenador/equipa.php
M	UI/RH/colaborador_novo.php
M	UI/RH/exportar.php
D	_doc/create_table_beneficios.sql
M	assets/CSS/Convidado/onboarding_convidado.css
M	composer.json
M	composer.lock
M	vendor/composer/autoload_psr4.php
M	vendor/composer/autoload_static.php
M	vendor/composer/installed.json
M	vendor/composer/installed.php
M	vendor/composer/platform_check.php

commit 5f77cbfdfd6916cf99067a435cb6c468a20720f6	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Fri Jul 4 13:03:58 2025 +0100

    provisorio

M	BLL/Comuns/BLL_forgot_password.php
M	BLL/Comuns/BLL_notificacoes.php
M	BLL/Coordenador/BLL_dashboard_coordenador.php
M	BLL/RH/BLL_colaboradores_gerir.php
M	BLL/RH/BLL_dashboard_rh.php
M	BLL/RH/BLL_equipa_editar.php
M	BLL/RH/BLL_exportar.php
A	BLL/RH/BLL_formacoes_gerir.php
A	BLL/RH/BLL_gerir_beneficios.php
A	BLL/RH/BLL_recibos_vencimento.php
A	BLL/RH/DAL_colaboradores_gerir.php
M	DAL/Comuns/DAL_notificacoes.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
M	DAL/RH/DAL_colaboradores_gerir.php
M	DAL/RH/DAL_dashboard_rh.php
M	DAL/RH/DAL_equipa_editar.php
M	DAL/RH/DAL_exportar.php
A	DAL/RH/DAL_gerir_beneficios.php
A	DAL/RH/DAL_gerir_formacoes.php
A	DAL/RH/DAL_recibos_vencimento.php
D	Database/create_inscricao_formacoes.sql
D	Database/create_tables.sql
D	Database/fix_inscricoes_formacao.sql
M	UI/Colaborador/beneficios.php
M	UI/Colaborador/dashboard_colaborador.php
M	UI/Colaborador/ferias.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Colaborador/formacoes.php
M	UI/Colaborador/pagina_inicial_colaborador.php
M	UI/Colaborador/recibos.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Convidado/onboarding_convidado.php
M	UI/Coordenador/dashboard_coordenador.php
M	UI/Coordenador/equipa.php
M	UI/Coordenador/pagina_inicial_coordenador.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipa.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
A	UI/RH/gerir_beneficios.php
A	UI/RH/gerir_formacoes.php
A	UI/RH/gerir_recibos.php
M	UI/RH/pagina_inicial_RH.php
M	UI/RH/recibos_submeter.php
M	UI/RH/relatorios.php
A	_doc/create_table_beneficios.sql
M	assets/CSS/Colaborador/ficha_colaborador.css
M	assets/CSS/Comuns/notificacoes.css
M	assets/CSS/Comuns/perfil.css
A	assets/CSS/Convidado/onboarding_convidado.css
M	assets/CSS/Coordenador/dashboard_coordenador.css
M	assets/CSS/Coordenador/relatorios_equipa.css
M	assets/CSS/RH/colaborador_novo.css
M	assets/CSS/RH/colaboradores_gerir.css
M	assets/CSS/RH/dashboard_rh.css
M	assets/CSS/RH/equipa_editar.css
M	assets/CSS/RH/equipa_nova.css
M	assets/CSS/RH/equipas.css
M	assets/CSS/RH/exportar.css
A	assets/CSS/RH/gerir_beneficios.css
A	assets/CSS/RH/gerir_formacoes.css
A	assets/CSS/RH/gerir_recibos.css
M	assets/CSS/RH/pagina_inicial.css
R100	BLL/Comuns/BLL_email.php	assets/CSS/RH/pagina_inicial_RH.css
A	assets/CSS/RH/recibos_submeter.css
M	assets/CSS/RH/relatorios.css
A	uploads/Recibos/1231247062025.pdf
A	uploads/Recibos/1231247_03_2025.pdf
A	uploads/Recibos/1231247_06_2021.pdf

commit 1d1c2a4cc75ec727bdc859cebde813799ef7d7fa	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Thu Jul 3 16:09:26 2025 +0100

    16h09

M	BLL/Colaborador/BLL_ficha_colaborador.php
M	BLL/Colaborador/BLL_formacoes.php
M	BLL/Colaborador/BLL_inscricoes.php
A	BLL/Comuns/BLL_email.php
M	BLL/Comuns/BLL_notificacoes.php
M	BLL/Comuns/BLL_perfil.php
M	BLL/Coordenador/BLL_dashboard_coordenador.php
M	BLL/RH/BLL_colaboradores_gerir.php
A	BLL/RH/BLL_recibos.php
M	DAL/Colaborador/DAL_ferias.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
M	DAL/Colaborador/DAL_formacoes.php
M	DAL/Colaborador/DAL_inscricoes.php
M	DAL/Comuns/DAL_notificacoes.php
M	DAL/Comuns/DAL_perfil.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
M	DAL/RH/DAL_colaboradores_gerir.php
A	DAL/RH/DAL_recibos.php
A	Database/create_inscricao_formacoes.sql
A	Database/create_tables.sql
A	Database/fix_inscricoes_formacao.sql
M	UI/Colaborador/beneficios.php
M	UI/Colaborador/ferias.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Colaborador/formacoes.php
M	UI/Colaborador/inscrever_formacao.php
M	UI/Colaborador/pedir_ferias.php
M	UI/Colaborador/recibos.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Coordenador/equipa.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/exportar.php
A	UI/RH/recibos_submeter.php
M	assets/CSS/Colaborador/beneficios.css
M	assets/CSS/Colaborador/ferias.css
M	assets/CSS/Colaborador/ficha_colaborador.css
M	assets/CSS/Colaborador/formacoes.css
M	assets/CSS/Colaborador/pagina_inicial_colaborador.css
M	assets/CSS/Colaborador/recibos_vencimento.css
M	assets/CSS/Comuns/notificacoes.css
M	assets/CSS/Comuns/perfil.css
M	assets/CSS/Coordenador/equipa.css
A	uploads/Recibos/recibo_6_2025_6_1751543154.pdf
A	uploads/Recibos/recibo_6_2025_6_1751543237.pdf
A	uploads/comprovativos/comprovativo_cc_8_1751542638.pdf
A	uploads/comprovativos/comprovativo_cc_8_1751543005.pdf
A	uploads/comprovativos/comprovativo_cc_8_1751543339.pdf
A	uploads/comprovativos/comprovativo_cc_8_1751543406.pdf
M	vendor/composer/installed.php

commit 737ee1a3c116a0ce463125ed5918e5259339dfbd	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Wed Jul 2 12:06:37 2025 +0100

    12h06

M	DAL/Coordenador/DAL_dashboard_coordenador.php
M	UI/Colaborador/beneficios.php
M	UI/Colaborador/ferias.php
M	UI/Colaborador/formacoes.php
M	UI/Colaborador/recibos.php
M	UI/Comuns/notificacoes.php
M	UI/Coordenador/equipa.php
M	UI/Coordenador/pagina_inicial_coordenador.php
M	UI/Coordenador/relatorios_equipa.php
D	processar_alertas.php

commit bbc0281dd51f6b425657b46c83f6f4fbdebeace8	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Wed Jul 2 10:56:08 2025 +0100

    10h56

M	BLL/Admin/BLL_alerta_novo.php
M	BLL/Admin/BLL_alertas.php
M	BLL/Coordenador/BLL_dashboard_coordenador.php
M	BLL/RH/BLL_dashboard_rh.php
M	BLL/RH/BLL_equipa_editar.php
M	DAL/Admin/DAL_alertas.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
M	DAL/RH/DAL_equipa_editar.php
M	UI/Admin/alerta_novo.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipa.php
A	processar_alertas.php

commit 12db03e8e2e7a7cad177084b6e98eb55c1d793b8	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Wed Jul 2 09:25:30 2025 +0100

    2/7 9h22

A	BLL/Colaborador/BLL_ferias.php
M	BLL/Colaborador/BLL_ficha_colaborador.php
A	BLL/Colaborador/BLL_formacoes.php
A	BLL/Colaborador/BLL_inscricoes.php
A	BLL/Colaborador/BLL_recibos_vencimento.php
M	BLL/Comuns/BLL_notificacoes.php
M	BLL/Comuns/BLL_perfil.php
M	BLL/RH/BLL_equipas.php
A	DAL/Colaborador/DAL_ferias.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
A	DAL/Colaborador/DAL_formacoes.php
A	DAL/Colaborador/DAL_inscricoes.php
A	DAL/Colaborador/DAL_recibos_vencimento.php
M	DAL/Comuns/DAL_perfil.php
M	DAL/RH/DAL_colaboradores_gerir.php
M	DAL/RH/DAL_dashboard_rh.php
A	UI/Colaborador/beneficios.php
A	UI/Colaborador/ferias.php
A	UI/Colaborador/formacoes.php
A	UI/Colaborador/inscrever_formacao.php
M	UI/Colaborador/pagina_inicial_colaborador.php
A	UI/Colaborador/pedir_ferias.php
A	UI/Colaborador/recibos.php
A	UI/Comuns/alterar_password.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Coordenador/equipa.php
M	UI/RH/colaboradores_gerir.php
A	assets/CSS/Colaborador/beneficios.css
A	assets/CSS/Colaborador/ferias.css
A	assets/CSS/Colaborador/formacoes.css
M	assets/CSS/Colaborador/pagina_inicial_colaborador.css
A	assets/CSS/Colaborador/recibos_vencimento.css
M	assets/CSS/Comuns/perfil.css
M	assets/CSS/Coordenador/equipa.css

commit 5d0ae54c195bd04744cd313d73abdbfc479f88e0	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Tue Jul 1 18:13:01 2025 +0100

    Lógica de tipos de equipa funcional

M	BLL/RH/BLL_dashboard_rh.php
M	BLL/RH/BLL_equipa_nova.php
M	BLL/RH/BLL_equipas.php
M	DAL/RH/DAL_dashboard_rh.php
M	DAL/RH/DAL_equipa_editar.php
M	DAL/RH/DAL_equipa_nova.php
M	DAL/RH/DAL_equipas.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipa.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
M	assets/CSS/RH/dashboard_rh.css

commit 1888dc7d4698a9a1a69d0b6b8519072205d68e04	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Tue Jul 1 14:58:36 2025 +0100

    Sem email e password

M	BLL/Comuns/BLL_forgot_password.php
M	BLL/Comuns/BLL_notificacoes.php

commit b9b47c6f3465f9a9a7407cfbc3ebe09dd272bc67	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Tue Jul 1 14:54:10 2025 +0100

    sem password

M	BLL/Comuns/BLL_forgot_password.php
M	BLL/Comuns/BLL_notificacoes.php

commit 73dd5ecb59375d4a8a63fabac1152f9ef220b73f	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Tue Jul 1 13:47:22 2025 +0100

    Adiciona pasta vendor/phpmailer ao repositório

A	vendor/phpmailer/phpmailer

commit 6b1238d9d0139038b3508e4e1135349ebdedd8ca	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Tue Jul 1 13:45:35 2025 +0100

    13h45

A	BLL/Admin/BLL_alerta_novo.php
A	BLL/Comuns/BLL_forgot_password.php
M	BLL/Comuns/BLL_notificacoes.php
A	DAL/Admin/DAL_alerta_novo.php
A	DAL/Comuns/DAL_forgot_password.php
M	DAL/Comuns/DAL_notificacoes.php
A	UI/Admin/alerta_novo.php
A	UI/Comuns/forgot_password.php
M	UI/Comuns/notificacoes.php
A	assets/CSS/Admin/alerta_novo.css
M	assets/CSS/Comuns/forgot_password.css
A	composer.json
A	composer.lock
A	vendor/autoload.php
A	vendor/composer/ClassLoader.php
A	vendor/composer/InstalledVersions.php
A	vendor/composer/LICENSE
A	vendor/composer/autoload_classmap.php
A	vendor/composer/autoload_namespaces.php
A	vendor/composer/autoload_psr4.php
A	vendor/composer/autoload_real.php
A	vendor/composer/autoload_static.php
A	vendor/composer/installed.json
A	vendor/composer/installed.php
A	vendor/composer/platform_check.php

commit c6b2a04c45e27c8b53b614f24f5d4396ac0a14b1	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Mon Jun 30 22:57:10 2025 +0100

    22h56

M	BLL/Comuns/BLL_notificacoes.php
M	DAL/Comuns/DAL_notificacoes.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Comuns/notificacoes.php
D	UI/RH/pedidos_aprovacao.php
A	assets/CSS/Comuns/forgot_password.css

commit e7ee6276101bc0d9e50b1e8b12aaab98fba2c83c	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Mon Jun 30 19:34:50 2025 +0100

    30/06

M	BLL/Colaborador/BLL_ficha_colaborador.php
M	BLL/Comuns/BLL_notificacoes.php
A	BLL/RH/BLL_equipa_editar.php
A	BLL/RH/BLL_equipa_nova.php
M	BLL/RH/BLL_equipas.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
M	DAL/Comuns/DAL_notificacoes.php
A	DAL/RH/DAL_equipa_editar.php
A	DAL/RH/DAL_equipa_nova.php
M	DAL/RH/DAL_equipas.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Comuns/notificacoes.php
M	UI/Coordenador/equipa.php
M	UI/Coordenador/pagina_inicial_coordenador.php
M	UI/RH/dashboard_rh.php
A	UI/RH/equipa.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
A	UI/RH/pedidos_aprovacao.php
A	assets/CSS/RH/equipa_editar.css
M	assets/CSS/RH/equipa_nova.css

commit d8b270b21c46b590035e430c555ea94c9405234c	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Mon Jun 30 14:17:45 2025 +0100

    CODIGO ATUALIZADO

M	BLL/Authenticator.php
A	BLL/Comuns/BLL_mensagens.php
M	BLL/Coordenador/BLL_dashboard_coordenador.php
A	DAL/Comuns/DAL_mensagens.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
M	GitAnalysis/Sprint_1/Commits.md
M	GitAnalysis/Sprint_1/Contributions.md
A	GitAnalysis/Sprint_2/Commits.md
A	GitAnalysis/Sprint_2/Contributions.md
M	UI/Colaborador/ficha_colaborador.php
A	UI/Comuns/enviar_mensagem.php
M	UI/Comuns/login.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Coordenador/dashboard_coordenador.php
M	UI/Coordenador/equipa.php
M	UI/Coordenador/pagina_inicial_coordenador.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/dashboard_rh.php
M	assets/CSS/Colaborador/ficha_colaborador.css
M	assets/CSS/Comuns/notificacoes.css
M	assets/CSS/Comuns/perfil.css
M	assets/CSS/Coordenador/dashboard_coordenador.css
M	assets/CSS/Coordenador/equipa.css
M	assets/CSS/Coordenador/pagina_inicial_coordenador.css
M	assets/CSS/Coordenador/relatorios_equipa.css
M	assets/CSS/RH/dashboard_rh.css
M	assets/CSS/RH/equipas.css
</pre>

</details>

#### 1231245@isep.ipp.pt
<details>
<summary>Diff</summary>

<pre>
 0 files changed
</pre>
</details>
<details>
<summary>Commits</summary>

<pre>
commit ecd1a86aec17926eba11975c5f49eaeac00de28a	refs/remotes/origin/miguel_branch (origin/miguel_branch)
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Sun Jul 6 16:58:59 2025 +0100

    ajustes pedidos

M	BLL/Colaborador/BLL_ficha_colaborador.php
M	BLL/Comuns/BLL_notificacoes.php
M	BLL/Coordenador/BLL_dashboard_coordenador.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
A	UI/Admin/dashboard_admin.php
M	UI/Comuns/login.php
M	UI/Comuns/notificacoes.php
A	uploads/comprovativos/comprovativo_cc_8_1751817452.pdf

commit 72fb6f58131555c829f04c301d28027f54552976	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Sun Jul 6 01:19:19 2025 +0100

    adições

M	BLL/Admin/BLL_alerta_novo.php
M	BLL/Admin/BLL_alertas.php
M	BLL/Colaborador/BLL_ficha_colaborador.php
A	BLL/Comuns/BLL_email.php
M	BLL/Comuns/BLL_notificacoes.php
A	BLL/Comuns/BLL_verificador_alertas.php
M	BLL/RH/BLL_relatorios.php
M	DAL/Admin/DAL_alerta_novo.php
M	DAL/Admin/DAL_alertas.php
M	DAL/Admin/DAL_permissoes.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
M	DAL/Comuns/DAL_notificacoes.php
M	DAL/RH/DAL_gerir_formacoes.php
M	DAL/RH/DAL_relatorios.php
M	UI/Admin/alerta_novo.php
M	UI/Admin/alertas.php
A	UI/Admin/alertas_fiscais.php
A	UI/Admin/alertas_vouchers.php
D	UI/Admin/campos_personalizados.php
D	UI/Admin/dashboard_admin.php
M	UI/Admin/pagina_inicial_admin.php
M	UI/Admin/permissoes.php
D	UI/Admin/utilizador_editar.php
D	UI/Admin/utilizador_novo.php
D	UI/Admin/utilizador_remover.php
M	UI/Admin/utilizadores.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Colaborador/formacoes.php
M	UI/Colaborador/pagina_inicial_colaborador.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/RH/dashboard_rh.php
M	UI/RH/gerir_formacoes.php
M	UI/RH/relatorios.php
M	UI/RH/relatorios_ajax.php
M	UI/RH/relatorios_pdf.php
A	assets/CSS/Admin/alertas_vouchers.css
M	assets/CSS/Colaborador/formacoes.css
A	cron/processar_alertas.php
A	cron/verificar_vouchers_automatico.php
A	cron/verificar_vouchers_diario.php
A	logs/.gitkeep
A	logs/emails.log

commit fa466b8b60f44583a17e88e98d270ce23881b38e	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Sat Jul 5 21:22:58 2025 +0100

    rh perfil completo

M	BLL/Colaborador/BLL_ficha_colaborador.php
M	BLL/RH/BLL_campos_personalizados.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
M	DAL/RH/DAL_campos_personalizados.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/RH/campos_personalizados.php
M	UI/RH/colaborador_novo.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipa.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
M	UI/RH/gerir_beneficios.php
M	UI/RH/gerir_formacoes.php
M	UI/RH/gerir_recibos.php
M	UI/RH/pagina_inicial_RH.php
M	UI/RH/recibos_submeter.php
M	UI/RH/relatorios.php
M	assets/CSS/RH/campos_personalizados.css

commit 5a5ad159ff9fc31d021c3d8344cef5e09b676e33	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Sat Jul 5 20:09:53 2025 +0100

    mudançlas

M	BLL/RH/BLL_relatorios.php
M	DAL/RH/DAL_relatorios.php
M	UI/RH/relatorios.php
M	UI/RH/relatorios_pdf.php
M	assets/CSS/RH/relatorios.css
M	vendor/composer/installed.php
A	vendor/fpdf/FAQ.htm
A	vendor/fpdf/changelog.htm
A	vendor/fpdf/doc/__construct.htm
A	vendor/fpdf/doc/acceptpagebreak.htm
A	vendor/fpdf/doc/addfont.htm
A	vendor/fpdf/doc/addlink.htm
A	vendor/fpdf/doc/addpage.htm
A	vendor/fpdf/doc/aliasnbpages.htm
A	vendor/fpdf/doc/cell.htm
A	vendor/fpdf/doc/close.htm
A	vendor/fpdf/doc/error.htm
A	vendor/fpdf/doc/footer.htm
A	vendor/fpdf/doc/getpageheight.htm
A	vendor/fpdf/doc/getpagewidth.htm
A	vendor/fpdf/doc/getstringwidth.htm
A	vendor/fpdf/doc/getx.htm
A	vendor/fpdf/doc/gety.htm
A	vendor/fpdf/doc/header.htm
A	vendor/fpdf/doc/image.htm
A	vendor/fpdf/doc/index.htm
A	vendor/fpdf/doc/line.htm
A	vendor/fpdf/doc/link.htm
A	vendor/fpdf/doc/ln.htm
A	vendor/fpdf/doc/multicell.htm
A	vendor/fpdf/doc/output.htm
A	vendor/fpdf/doc/pageno.htm
A	vendor/fpdf/doc/rect.htm
A	vendor/fpdf/doc/setauthor.htm
A	vendor/fpdf/doc/setautopagebreak.htm
A	vendor/fpdf/doc/setcompression.htm
A	vendor/fpdf/doc/setcreator.htm
A	vendor/fpdf/doc/setdisplaymode.htm
A	vendor/fpdf/doc/setdrawcolor.htm
A	vendor/fpdf/doc/setfillcolor.htm
A	vendor/fpdf/doc/setfont.htm
A	vendor/fpdf/doc/setfontsize.htm
A	vendor/fpdf/doc/setkeywords.htm
A	vendor/fpdf/doc/setleftmargin.htm
A	vendor/fpdf/doc/setlinewidth.htm
A	vendor/fpdf/doc/setlink.htm
A	vendor/fpdf/doc/setmargins.htm
A	vendor/fpdf/doc/setrightmargin.htm
A	vendor/fpdf/doc/setsubject.htm
A	vendor/fpdf/doc/settextcolor.htm
A	vendor/fpdf/doc/settitle.htm
A	vendor/fpdf/doc/settopmargin.htm
A	vendor/fpdf/doc/setx.htm
A	vendor/fpdf/doc/setxy.htm
A	vendor/fpdf/doc/sety.htm
A	vendor/fpdf/doc/text.htm
A	vendor/fpdf/doc/write.htm
A	vendor/fpdf/font/courier.php
A	vendor/fpdf/font/courierb.php
A	vendor/fpdf/font/courierbi.php
A	vendor/fpdf/font/courieri.php
A	vendor/fpdf/font/helvetica.php
A	vendor/fpdf/font/helveticab.php
A	vendor/fpdf/font/helveticabi.php
A	vendor/fpdf/font/helveticai.php
A	vendor/fpdf/font/symbol.php
A	vendor/fpdf/font/times.php
A	vendor/fpdf/font/timesb.php
A	vendor/fpdf/font/timesbi.php
A	vendor/fpdf/font/timesi.php
A	vendor/fpdf/font/zapfdingbats.php
A	vendor/fpdf/fpdf.css
A	vendor/fpdf/fpdf.php
A	vendor/fpdf/install.txt
A	vendor/fpdf/license.txt
A	vendor/fpdf/makefont/cp1250.map
A	vendor/fpdf/makefont/cp1251.map
A	vendor/fpdf/makefont/cp1252.map
A	vendor/fpdf/makefont/cp1253.map
A	vendor/fpdf/makefont/cp1254.map
A	vendor/fpdf/makefont/cp1255.map
A	vendor/fpdf/makefont/cp1257.map
A	vendor/fpdf/makefont/cp1258.map
A	vendor/fpdf/makefont/cp874.map
A	vendor/fpdf/makefont/iso-8859-1.map
A	vendor/fpdf/makefont/iso-8859-11.map
A	vendor/fpdf/makefont/iso-8859-15.map
A	vendor/fpdf/makefont/iso-8859-16.map
A	vendor/fpdf/makefont/iso-8859-2.map
A	vendor/fpdf/makefont/iso-8859-4.map
A	vendor/fpdf/makefont/iso-8859-5.map
A	vendor/fpdf/makefont/iso-8859-7.map
A	vendor/fpdf/makefont/iso-8859-9.map
A	vendor/fpdf/makefont/koi8-r.map
A	vendor/fpdf/makefont/koi8-u.map
A	vendor/fpdf/makefont/makefont.php
A	vendor/fpdf/makefont/ttfparser.php
A	vendor/fpdf/tutorial/20k_c1.txt
A	vendor/fpdf/tutorial/20k_c2.txt
A	vendor/fpdf/tutorial/CevicheOne-Regular-Licence.txt
A	vendor/fpdf/tutorial/CevicheOne-Regular.php
A	vendor/fpdf/tutorial/CevicheOne-Regular.ttf
A	vendor/fpdf/tutorial/CevicheOne-Regular.z
A	vendor/fpdf/tutorial/countries.txt
A	vendor/fpdf/tutorial/index.htm
A	vendor/fpdf/tutorial/logo.png
A	vendor/fpdf/tutorial/makefont.php
A	vendor/fpdf/tutorial/tuto1.htm
A	vendor/fpdf/tutorial/tuto1.php
A	vendor/fpdf/tutorial/tuto2.htm
A	vendor/fpdf/tutorial/tuto2.php
A	vendor/fpdf/tutorial/tuto3.htm
A	vendor/fpdf/tutorial/tuto3.php
A	vendor/fpdf/tutorial/tuto4.htm
A	vendor/fpdf/tutorial/tuto4.php
A	vendor/fpdf/tutorial/tuto5.htm
A	vendor/fpdf/tutorial/tuto5.php
A	vendor/fpdf/tutorial/tuto6.htm
A	vendor/fpdf/tutorial/tuto6.php
A	vendor/fpdf/tutorial/tuto7.htm
A	vendor/fpdf/tutorial/tuto7.php
M	vendor/mpdf/mpdf/mpdf.php

commit 21b31066e700c167431783618d9030d694c04384	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Sat Jul 5 19:26:39 2025 +0100

    importar e campos personalizados
    
    importar e campos personalizados

A	BLL/RH/BLL_campos_personalizados.php
M	BLL/RH/BLL_colaboradores_gerir.php
M	BLL/RH/BLL_relatorios.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
A	DAL/RH/DAL_campos_personalizados.php
M	DAL/RH/DAL_colaboradores_gerir.php
M	DAL/RH/DAL_relatorios.php
A	UI/RH/campos_personalizados.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/relatorios.php
A	UI/RH/relatorios_ajax.php
A	UI/RH/relatorios_pdf.php
A	assets/CSS/RH/campos_personalizados.css
M	assets/CSS/RH/relatorios.css
M	composer.json
M	composer.lock
M	vendor/composer/autoload_classmap.php
M	vendor/composer/autoload_static.php
M	vendor/composer/installed.json
M	vendor/composer/installed.php
A	vendor/mpdf/mpdf/.gitignore
A	vendor/mpdf/mpdf/.travis.yml
A	vendor/mpdf/mpdf/CHANGELOG.txt
A	vendor/mpdf/mpdf/CREDITS.txt
A	vendor/mpdf/mpdf/LICENSE.txt
A	vendor/mpdf/mpdf/MpdfException.php
A	vendor/mpdf/mpdf/README.md
A	vendor/mpdf/mpdf/Tag.php
A	vendor/mpdf/mpdf/classes/barcode.php
A	vendor/mpdf/mpdf/classes/bmp.php
A	vendor/mpdf/mpdf/classes/cssmgr.php
A	vendor/mpdf/mpdf/classes/directw.php
A	vendor/mpdf/mpdf/classes/gif.php
A	vendor/mpdf/mpdf/classes/grad.php
A	vendor/mpdf/mpdf/classes/indic.php
A	vendor/mpdf/mpdf/classes/meter.php
A	vendor/mpdf/mpdf/classes/mpdfform.php
A	vendor/mpdf/mpdf/classes/myanmar.php
A	vendor/mpdf/mpdf/classes/otl.php
A	vendor/mpdf/mpdf/classes/otl_dump.php
A	vendor/mpdf/mpdf/classes/sea.php
A	vendor/mpdf/mpdf/classes/svg.php
A	vendor/mpdf/mpdf/classes/tocontents.php
A	vendor/mpdf/mpdf/classes/ttfontsuni.php
A	vendor/mpdf/mpdf/classes/ttfontsuni_analysis.php
A	vendor/mpdf/mpdf/classes/ucdn.php
A	vendor/mpdf/mpdf/classes/wmf.php
A	vendor/mpdf/mpdf/collations/Afrikaans_South_Africa.php
A	vendor/mpdf/mpdf/collations/Albanian_Albania.php
A	vendor/mpdf/mpdf/collations/Alsatian_France.php
A	vendor/mpdf/mpdf/collations/Arabic_Algeria.php
A	vendor/mpdf/mpdf/collations/Arabic_Bahrain.php
A	vendor/mpdf/mpdf/collations/Arabic_Egypt.php
A	vendor/mpdf/mpdf/collations/Arabic_Iraq.php
A	vendor/mpdf/mpdf/collations/Arabic_Jordan.php
A	vendor/mpdf/mpdf/collations/Arabic_Kuwait.php
A	vendor/mpdf/mpdf/collations/Arabic_Lebanon.php
A	vendor/mpdf/mpdf/collations/Arabic_Libya.php
A	vendor/mpdf/mpdf/collations/Arabic_Morocco.php
A	vendor/mpdf/mpdf/collations/Arabic_Oman.php
A	vendor/mpdf/mpdf/collations/Arabic_Pseudo_RTL.php
A	vendor/mpdf/mpdf/collations/Arabic_Qatar.php
A	vendor/mpdf/mpdf/collations/Arabic_Saudi_Arabia.php
A	vendor/mpdf/mpdf/collations/Arabic_Syria.php
A	vendor/mpdf/mpdf/collations/Arabic_Tunisia.php
A	vendor/mpdf/mpdf/collations/Arabic_Yemen.php
A	vendor/mpdf/mpdf/collations/Azeri_(Cyrillic)_Azerbaijan.php
A	vendor/mpdf/mpdf/collations/Azeri_(Latin)_Azerbaijan.php
A	vendor/mpdf/mpdf/collations/Bashkir_Russia.php
A	vendor/mpdf/mpdf/collations/Basque_Spain.php
A	vendor/mpdf/mpdf/collations/Belarusian_Belarus.php
A	vendor/mpdf/mpdf/collations/Bosnian_(Cyrillic)_Bosnia_and_Herzegovina.php
A	vendor/mpdf/mpdf/collations/Bosnian_(Latin)_Bosnia_and_Herzegovina.php
A	vendor/mpdf/mpdf/collations/Breton_France.php
A	vendor/mpdf/mpdf/collations/Bulgarian_Bulgaria.php
A	vendor/mpdf/mpdf/collations/Catalan_Spain.php
A	vendor/mpdf/mpdf/collations/Corsican_France.php
A	vendor/mpdf/mpdf/collations/Croatian_(Latin)_Bosnia_and_Herzegovina.php
A	vendor/mpdf/mpdf/collations/Croatian_Croatia.php
A	vendor/mpdf/mpdf/collations/Czech_Czech_Republic.php
A	vendor/mpdf/mpdf/collations/Danish_Denmark.php
A	vendor/mpdf/mpdf/collations/Dari_Afghanistan.php
A	vendor/mpdf/mpdf/collations/Dutch_Belgium.php
A	vendor/mpdf/mpdf/collations/Dutch_Netherlands.php
A	vendor/mpdf/mpdf/collations/English_Australia.php
A	vendor/mpdf/mpdf/collations/English_Belize.php
A	vendor/mpdf/mpdf/collations/English_Canada.php
A	vendor/mpdf/mpdf/collations/English_Caribbean.php
A	vendor/mpdf/mpdf/collations/English_India.php
A	vendor/mpdf/mpdf/collations/English_Ireland.php
A	vendor/mpdf/mpdf/collations/English_Jamaica.php
A	vendor/mpdf/mpdf/collations/English_Malaysia.php
A	vendor/mpdf/mpdf/collations/English_New_Zealand.php
A	vendor/mpdf/mpdf/collations/English_Republic_of_the_Philippines.php
A	vendor/mpdf/mpdf/collations/English_Singapore.php
A	vendor/mpdf/mpdf/collations/English_South_Africa.php
A	vendor/mpdf/mpdf/collations/English_Trinidad_and_Tobago.php
A	vendor/mpdf/mpdf/collations/English_United_Kingdom.php
A	vendor/mpdf/mpdf/collations/English_United_States.php
A	vendor/mpdf/mpdf/collations/English_Zimbabwe.php
A	vendor/mpdf/mpdf/collations/Estonian_Estonia.php
A	vendor/mpdf/mpdf/collations/Faroese_Faroe_Islands.php
A	vendor/mpdf/mpdf/collations/Filipino_Philippines.php
A	vendor/mpdf/mpdf/collations/Finnish_Finland.php
A	vendor/mpdf/mpdf/collations/French_Belgium.php
A	vendor/mpdf/mpdf/collations/French_Canada.php
A	vendor/mpdf/mpdf/collations/French_France.php
A	vendor/mpdf/mpdf/collations/French_Luxembourg.php
A	vendor/mpdf/mpdf/collations/French_Principality_of_Monaco.php
A	vendor/mpdf/mpdf/collations/French_Switzerland.php
A	vendor/mpdf/mpdf/collations/Frisian_Netherlands.php
A	vendor/mpdf/mpdf/collations/Galician_Spain.php
A	vendor/mpdf/mpdf/collations/German_Austria.php
A	vendor/mpdf/mpdf/collations/German_Germany.php
A	vendor/mpdf/mpdf/collations/German_Liechtenstein.php
A	vendor/mpdf/mpdf/collations/German_Luxembourg.php
A	vendor/mpdf/mpdf/collations/German_Switzerland.php
A	vendor/mpdf/mpdf/collations/Greek_Greece.php
A	vendor/mpdf/mpdf/collations/Greenlandic_Greenland.php
A	vendor/mpdf/mpdf/collations/Hausa_(Latin)_Nigeria.php
A	vendor/mpdf/mpdf/collations/Hebrew_Israel.php
A	vendor/mpdf/mpdf/collations/Hungarian_Hungary.php
A	vendor/mpdf/mpdf/collations/Icelandic_Iceland.php
A	vendor/mpdf/mpdf/collations/Igbo_Nigeria.php
A	vendor/mpdf/mpdf/collations/Indonesian_Indonesia.php
A	vendor/mpdf/mpdf/collations/Inuktitut_(Latin)_Canada.php
A	vendor/mpdf/mpdf/collations/Invariant_Language_Invariant_Country.php
A	vendor/mpdf/mpdf/collations/Irish_Ireland.php
A	vendor/mpdf/mpdf/collations/Italian_Italy.php
A	vendor/mpdf/mpdf/collations/Italian_Switzerland.php
A	vendor/mpdf/mpdf/collations/Kinyarwanda_Rwanda.php
A	vendor/mpdf/mpdf/collations/Kiswahili_Kenya.php
A	vendor/mpdf/mpdf/collations/Kyrgyz_Kyrgyzstan.php
A	vendor/mpdf/mpdf/collations/Latvian_Latvia.php
A	vendor/mpdf/mpdf/collations/Lithuanian_Lithuania.php
A	vendor/mpdf/mpdf/collations/Lower_Sorbian_Germany.php
A	vendor/mpdf/mpdf/collations/Luxembourgish_Luxembourg.php
A	vendor/mpdf/mpdf/collations/Macedonian_(FYROM)_Macedonia_(FYROM).php
A	vendor/mpdf/mpdf/collations/Malay_Brunei_Darussalam.php
A	vendor/mpdf/mpdf/collations/Malay_Malaysia.php
A	vendor/mpdf/mpdf/collations/Mapudungun_Chile.php
A	vendor/mpdf/mpdf/collations/Mohawk_Canada.php
A	vendor/mpdf/mpdf/collations/Mongolian_(Cyrillic)_Mongolia.php
A	vendor/mpdf/mpdf/collations/Norwegian_(Nynorsk)_Norway.php
A	vendor/mpdf/mpdf/collations/Occitan_France.php
A	vendor/mpdf/mpdf/collations/Persian_Iran.php
A	vendor/mpdf/mpdf/collations/Polish_Poland.php
A	vendor/mpdf/mpdf/collations/Portuguese_Brazil.php
A	vendor/mpdf/mpdf/collations/Portuguese_Portugal.php
A	vendor/mpdf/mpdf/collations/Quechua_Bolivia.php
A	vendor/mpdf/mpdf/collations/Quechua_Ecuador.php
A	vendor/mpdf/mpdf/collations/Quechua_Peru.php
A	vendor/mpdf/mpdf/collations/Romanian_Romania.php
A	vendor/mpdf/mpdf/collations/Romansh_Switzerland.php
A	vendor/mpdf/mpdf/collations/Russian_Russia.php
A	vendor/mpdf/mpdf/collations/Sami_(Inari)_Finland.php
A	vendor/mpdf/mpdf/collations/Sami_(Lule)_Norway.php
A	vendor/mpdf/mpdf/collations/Sami_(Lule)_Sweden.php
A	vendor/mpdf/mpdf/collations/Sami_(Northern)_Finland.php
A	vendor/mpdf/mpdf/collations/Sami_(Northern)_Norway.php
A	vendor/mpdf/mpdf/collations/Sami_(Northern)_Sweden.php
A	vendor/mpdf/mpdf/collations/Sami_(Skolt)_Finland.php
A	vendor/mpdf/mpdf/collations/Sami_(Southern)_Norway.php
A	vendor/mpdf/mpdf/collations/Sami_(Southern)_Sweden.php
A	vendor/mpdf/mpdf/collations/Serbian_(Cyrillic)_Bosnia_and_Herzegovina.php
A	vendor/mpdf/mpdf/collations/Serbian_(Cyrillic)_Serbia.php
A	vendor/mpdf/mpdf/collations/Serbian_(Latin)_Bosnia_and_Herzegovina.php
A	vendor/mpdf/mpdf/collations/Serbian_(Latin)_Serbia.php
A	vendor/mpdf/mpdf/collations/Sesotho_sa_Leboa_South_Africa.php
A	vendor/mpdf/mpdf/collations/Setswana_South_Africa.php
A	vendor/mpdf/mpdf/collations/Slovak_Slovakia.php
A	vendor/mpdf/mpdf/collations/Slovenian_Slovenia.php
A	vendor/mpdf/mpdf/collations/Spanish_Argentina.php
A	vendor/mpdf/mpdf/collations/Spanish_Bolivia.php
A	vendor/mpdf/mpdf/collations/Spanish_Chile.php
A	vendor/mpdf/mpdf/collations/Spanish_Colombia.php
A	vendor/mpdf/mpdf/collations/Spanish_Costa_Rica.php
A	vendor/mpdf/mpdf/collations/Spanish_Dominican_Republic.php
A	vendor/mpdf/mpdf/collations/Spanish_Ecuador.php
A	vendor/mpdf/mpdf/collations/Spanish_El_Salvador.php
A	vendor/mpdf/mpdf/collations/Spanish_Guatemala.php
A	vendor/mpdf/mpdf/collations/Spanish_Honduras.php
A	vendor/mpdf/mpdf/collations/Spanish_Mexico.php
A	vendor/mpdf/mpdf/collations/Spanish_Nicaragua.php
A	vendor/mpdf/mpdf/collations/Spanish_Panama.php
A	vendor/mpdf/mpdf/collations/Spanish_Paraguay.php
A	vendor/mpdf/mpdf/collations/Spanish_Peru.php
A	vendor/mpdf/mpdf/collations/Spanish_Puerto_Rico.php
A	vendor/mpdf/mpdf/collations/Spanish_Spain.php
A	vendor/mpdf/mpdf/collations/Spanish_United_States.php
A	vendor/mpdf/mpdf/collations/Spanish_Uruguay.php
A	vendor/mpdf/mpdf/collations/Spanish_Venezuela.php
A	vendor/mpdf/mpdf/collations/Swedish_Finland.php
A	vendor/mpdf/mpdf/collations/Swedish_Sweden.php
A	vendor/mpdf/mpdf/collations/Tajik_(Cyrillic)_Tajikistan.php
A	vendor/mpdf/mpdf/collations/Tamazight_(Latin)_Algeria.php
A	vendor/mpdf/mpdf/collations/Tatar_Russia.php
A	vendor/mpdf/mpdf/collations/Turkish_Turkey.php
A	vendor/mpdf/mpdf/collations/Turkmen_Turkmenistan.php
A	vendor/mpdf/mpdf/collations/Ukrainian_Ukraine.php
A	vendor/mpdf/mpdf/collations/Upper_Sorbian_Germany.php
A	vendor/mpdf/mpdf/collations/Urdu_Islamic_Republic_of_Pakistan.php
A	vendor/mpdf/mpdf/collations/Uzbek_(Cyrillic)_Uzbekistan.php
A	vendor/mpdf/mpdf/collations/Uzbek_(Latin)_Uzbekistan.php
A	vendor/mpdf/mpdf/collations/Vietnamese_Vietnam.php
A	vendor/mpdf/mpdf/collations/Welsh_United_Kingdom.php
A	vendor/mpdf/mpdf/collations/Wolof_Senegal.php
A	vendor/mpdf/mpdf/collations/Yakut_Russia.php
A	vendor/mpdf/mpdf/collations/Yoruba_Nigeria.php
A	vendor/mpdf/mpdf/collations/isiXhosa_South_Africa.php
A	vendor/mpdf/mpdf/collations/isiZulu_South_Africa.php
A	vendor/mpdf/mpdf/composer.json
A	vendor/mpdf/mpdf/compress.php
A	vendor/mpdf/mpdf/config.php
A	vendor/mpdf/mpdf/config_fonts-distr-without-OTL.php
A	vendor/mpdf/mpdf/config_fonts.php
A	vendor/mpdf/mpdf/config_lang2fonts.php
A	vendor/mpdf/mpdf/config_script2lang.php
A	vendor/mpdf/mpdf/font/ccourier.php
A	vendor/mpdf/mpdf/font/ccourierb.php
A	vendor/mpdf/mpdf/font/ccourierbi.php
A	vendor/mpdf/mpdf/font/ccourieri.php
A	vendor/mpdf/mpdf/font/chelvetica.php
A	vendor/mpdf/mpdf/font/chelveticab.php
A	vendor/mpdf/mpdf/font/chelveticabi.php
A	vendor/mpdf/mpdf/font/chelveticai.php
A	vendor/mpdf/mpdf/font/csymbol.php
A	vendor/mpdf/mpdf/font/ctimes.php
A	vendor/mpdf/mpdf/font/ctimesb.php
A	vendor/mpdf/mpdf/font/ctimesbi.php
A	vendor/mpdf/mpdf/font/ctimesi.php
A	vendor/mpdf/mpdf/font/czapfdingbats.php
A	vendor/mpdf/mpdf/graph.php
A	vendor/mpdf/mpdf/graph_cache/.gitignore
A	vendor/mpdf/mpdf/iccprofiles/SWOP2006_Coated5v2.icc
A	vendor/mpdf/mpdf/iccprofiles/sRGB_IEC61966-2-1.icc
A	vendor/mpdf/mpdf/includes/CJKdata.php
A	vendor/mpdf/mpdf/includes/functions.php
A	vendor/mpdf/mpdf/includes/linebrdictK.dat
A	vendor/mpdf/mpdf/includes/linebrdictL.dat
A	vendor/mpdf/mpdf/includes/linebrdictT.dat
A	vendor/mpdf/mpdf/includes/no_image.jpg
A	vendor/mpdf/mpdf/includes/out.php
A	vendor/mpdf/mpdf/includes/subs_core.php
A	vendor/mpdf/mpdf/includes/subs_win-1252.php
A	vendor/mpdf/mpdf/includes/upperCase.php
A	vendor/mpdf/mpdf/lang2fonts.css
A	vendor/mpdf/mpdf/mpdf.css
A	vendor/mpdf/mpdf/mpdf.php
A	vendor/mpdf/mpdf/patterns/NOTES.txt
A	vendor/mpdf/mpdf/patterns/de.php
A	vendor/mpdf/mpdf/patterns/dictionary.txt
A	vendor/mpdf/mpdf/patterns/en.php
A	vendor/mpdf/mpdf/patterns/es.php
A	vendor/mpdf/mpdf/patterns/fi.php
A	vendor/mpdf/mpdf/patterns/fr.php
A	vendor/mpdf/mpdf/patterns/it.php
A	vendor/mpdf/mpdf/patterns/nl.php
A	vendor/mpdf/mpdf/patterns/pl.php
A	vendor/mpdf/mpdf/patterns/ru.php
A	vendor/mpdf/mpdf/patterns/sv.php
A	vendor/mpdf/mpdf/phpunit.xml
A	vendor/mpdf/mpdf/progbar.css
A	vendor/mpdf/mpdf/qrcode/_LGPL.txt
A	vendor/mpdf/mpdf/qrcode/_lisez_moi.txt
A	vendor/mpdf/mpdf/qrcode/data/modele1.dat
A	vendor/mpdf/mpdf/qrcode/data/modele10.dat
A	vendor/mpdf/mpdf/qrcode/data/modele11.dat
A	vendor/mpdf/mpdf/qrcode/data/modele12.dat
A	vendor/mpdf/mpdf/qrcode/data/modele13.dat
A	vendor/mpdf/mpdf/qrcode/data/modele14.dat
A	vendor/mpdf/mpdf/qrcode/data/modele15.dat
A	vendor/mpdf/mpdf/qrcode/data/modele16.dat
A	vendor/mpdf/mpdf/qrcode/data/modele17.dat
A	vendor/mpdf/mpdf/qrcode/data/modele18.dat
A	vendor/mpdf/mpdf/qrcode/data/modele19.dat
A	vendor/mpdf/mpdf/qrcode/data/modele2.dat
A	vendor/mpdf/mpdf/qrcode/data/modele20.dat
A	vendor/mpdf/mpdf/qrcode/data/modele21.dat
A	vendor/mpdf/mpdf/qrcode/data/modele22.dat
A	vendor/mpdf/mpdf/qrcode/data/modele23.dat
A	vendor/mpdf/mpdf/qrcode/data/modele24.dat
A	vendor/mpdf/mpdf/qrcode/data/modele25.dat
A	vendor/mpdf/mpdf/qrcode/data/modele26.dat
A	vendor/mpdf/mpdf/qrcode/data/modele27.dat
A	vendor/mpdf/mpdf/qrcode/data/modele28.dat
A	vendor/mpdf/mpdf/qrcode/data/modele29.dat
A	vendor/mpdf/mpdf/qrcode/data/modele3.dat
A	vendor/mpdf/mpdf/qrcode/data/modele30.dat
A	vendor/mpdf/mpdf/qrcode/data/modele31.dat
A	vendor/mpdf/mpdf/qrcode/data/modele32.dat
A	vendor/mpdf/mpdf/qrcode/data/modele33.dat
A	vendor/mpdf/mpdf/qrcode/data/modele34.dat
A	vendor/mpdf/mpdf/qrcode/data/modele35.dat
A	vendor/mpdf/mpdf/qrcode/data/modele36.dat
A	vendor/mpdf/mpdf/qrcode/data/modele37.dat
A	vendor/mpdf/mpdf/qrcode/data/modele38.dat
A	vendor/mpdf/mpdf/qrcode/data/modele39.dat
A	vendor/mpdf/mpdf/qrcode/data/modele4.dat
A	vendor/mpdf/mpdf/qrcode/data/modele40.dat
A	vendor/mpdf/mpdf/qrcode/data/modele5.dat
A	vendor/mpdf/mpdf/qrcode/data/modele6.dat
A	vendor/mpdf/mpdf/qrcode/data/modele7.dat
A	vendor/mpdf/mpdf/qrcode/data/modele8.dat
A	vendor/mpdf/mpdf/qrcode/data/modele9.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv10_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv10_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv10_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv10_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv11_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv11_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv11_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv11_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv12_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv12_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv12_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv12_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv13_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv13_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv13_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv13_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv14_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv14_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv14_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv14_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv15_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv15_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv15_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv15_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv16_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv16_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv16_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv16_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv17_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv17_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv17_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv17_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv18_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv18_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv18_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv18_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv19_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv19_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv19_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv19_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv1_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv1_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv1_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv1_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv20_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv20_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv20_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv20_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv21_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv21_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv21_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv21_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv22_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv22_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv22_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv22_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv23_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv23_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv23_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv23_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv24_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv24_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv24_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv24_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv25_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv25_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv25_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv25_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv26_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv26_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv26_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv26_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv27_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv27_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv27_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv27_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv28_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv28_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv28_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv28_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv29_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv29_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv29_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv29_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv2_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv2_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv2_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv2_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv30_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv30_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv30_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv30_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv31_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv31_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv31_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv31_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv32_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv32_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv32_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv32_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv33_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv33_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv33_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv33_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv34_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv34_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv34_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv34_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv35_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv35_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv35_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv35_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv36_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv36_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv36_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv36_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv37_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv37_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv37_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv37_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv38_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv38_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv38_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv38_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv39_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv39_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv39_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv39_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv3_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv3_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv3_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv3_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv40_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv40_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv40_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv40_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv4_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv4_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv4_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv4_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv5_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv5_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv5_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv5_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv6_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv6_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv6_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv6_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv7_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv7_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv7_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv7_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv8_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv8_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv8_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv8_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv9_0.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv9_1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv9_2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrv9_3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr1.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr10.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr11.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr12.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr13.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr14.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr15.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr16.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr17.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr18.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr19.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr2.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr20.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr21.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr22.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr23.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr24.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr25.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr26.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr27.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr28.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr29.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr3.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr30.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr31.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr32.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr33.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr34.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr35.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr36.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr37.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr38.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr39.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr4.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr40.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr5.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr6.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr7.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr8.dat
A	vendor/mpdf/mpdf/qrcode/data/qrvfr9.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc10.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc13.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc15.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc16.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc17.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc18.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc20.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc22.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc24.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc26.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc28.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc30.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc32.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc34.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc36.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc40.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc42.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc44.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc46.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc48.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc50.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc52.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc54.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc56.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc58.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc60.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc62.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc64.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc66.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc68.dat
A	vendor/mpdf/mpdf/qrcode/data/rsc7.dat
A	vendor/mpdf/mpdf/qrcode/image.php
A	vendor/mpdf/mpdf/qrcode/index.php
A	vendor/mpdf/mpdf/qrcode/qrcode.class.php
A	vendor/mpdf/mpdf/tmp/.gitignore
A	vendor/mpdf/mpdf/ttfontdata/.gitignore
A	vendor/mpdf/mpdf/ttfonts/AboriginalSansREGULAR.ttf
A	vendor/mpdf/mpdf/ttfonts/Abyssinica_SIL.ttf
A	vendor/mpdf/mpdf/ttfonts/Aegean.otf
A	vendor/mpdf/mpdf/ttfonts/Aegyptus.otf
A	vendor/mpdf/mpdf/ttfonts/Akkadian.otf
A	vendor/mpdf/mpdf/ttfonts/DBSILBR.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSans-Bold.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSans-BoldOblique.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSans-Oblique.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSans.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSansCondensed-Bold.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSansCondensed-BoldOblique.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSansCondensed-Oblique.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSansCondensed.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSansMono-Bold.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSansMono-BoldOblique.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSansMono-Oblique.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSansMono.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSerif-Bold.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSerif-BoldItalic.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSerif-Italic.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSerif.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSerifCondensed-Bold.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSerifCondensed-BoldItalic.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSerifCondensed-Italic.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuSerifCondensed.ttf
A	vendor/mpdf/mpdf/ttfonts/DejaVuinfo.txt
A	vendor/mpdf/mpdf/ttfonts/Dhyana-Bold.ttf
A	vendor/mpdf/mpdf/ttfonts/Dhyana-Regular.ttf
A	vendor/mpdf/mpdf/ttfonts/DhyanaOFL.txt
A	vendor/mpdf/mpdf/ttfonts/FreeMono.ttf
A	vendor/mpdf/mpdf/ttfonts/FreeMonoBold.ttf
A	vendor/mpdf/mpdf/ttfonts/FreeMonoBoldOblique.ttf
A	vendor/mpdf/mpdf/ttfonts/FreeMonoOblique.ttf
A	vendor/mpdf/mpdf/ttfonts/FreeSans.ttf
A	vendor/mpdf/mpdf/ttfonts/FreeSansBold.ttf
A	vendor/mpdf/mpdf/ttfonts/FreeSansBoldOblique.ttf
A	vendor/mpdf/mpdf/ttfonts/FreeSansOblique.ttf
A	vendor/mpdf/mpdf/ttfonts/FreeSerif.ttf
A	vendor/mpdf/mpdf/ttfonts/FreeSerifBold.ttf
A	vendor/mpdf/mpdf/ttfonts/FreeSerifBoldItalic.ttf
A	vendor/mpdf/mpdf/ttfonts/FreeSerifItalic.ttf
A	vendor/mpdf/mpdf/ttfonts/GNUFreeFontinfo.txt
A	vendor/mpdf/mpdf/ttfonts/Garuda-Bold.ttf
A	vendor/mpdf/mpdf/ttfonts/Garuda-BoldOblique.ttf
A	vendor/mpdf/mpdf/ttfonts/Garuda-Oblique.ttf
A	vendor/mpdf/mpdf/ttfonts/Garuda.ttf
A	vendor/mpdf/mpdf/ttfonts/Jomolhari-OFL.txt
A	vendor/mpdf/mpdf/ttfonts/Jomolhari.ttf
A	vendor/mpdf/mpdf/ttfonts/KhmerOFL.txt
A	vendor/mpdf/mpdf/ttfonts/KhmerOS.ttf
A	vendor/mpdf/mpdf/ttfonts/Lateef font OFL.txt
A	vendor/mpdf/mpdf/ttfonts/LateefRegOT.ttf
A	vendor/mpdf/mpdf/ttfonts/Lohit-Kannada.ttf
A	vendor/mpdf/mpdf/ttfonts/LohitKannadaOFL.txt
A	vendor/mpdf/mpdf/ttfonts/Padauk-book.ttf
A	vendor/mpdf/mpdf/ttfonts/Pothana2000.ttf
A	vendor/mpdf/mpdf/ttfonts/Quivira.otf
A	vendor/mpdf/mpdf/ttfonts/Sun-ExtA.ttf
A	vendor/mpdf/mpdf/ttfonts/Sun-ExtB.ttf
A	vendor/mpdf/mpdf/ttfonts/SundaneseUnicode-1.0.5.ttf
A	vendor/mpdf/mpdf/ttfonts/SyrCOMEdessa.otf
A	vendor/mpdf/mpdf/ttfonts/SyrCOMEdessa_license.txt
A	vendor/mpdf/mpdf/ttfonts/TaameyDavidCLM-LICENSE.txt
A	vendor/mpdf/mpdf/ttfonts/TaameyDavidCLM-Medium.ttf
A	vendor/mpdf/mpdf/ttfonts/TaiHeritagePro.ttf
A	vendor/mpdf/mpdf/ttfonts/Tharlon-Regular.ttf
A	vendor/mpdf/mpdf/ttfonts/TharlonOFL.txt
A	vendor/mpdf/mpdf/ttfonts/UnBatang_0613.ttf
A	vendor/mpdf/mpdf/ttfonts/Uthman.otf
A	vendor/mpdf/mpdf/ttfonts/XB Riyaz.ttf
A	vendor/mpdf/mpdf/ttfonts/XB RiyazBd.ttf
A	vendor/mpdf/mpdf/ttfonts/XB RiyazBdIt.ttf
A	vendor/mpdf/mpdf/ttfonts/XB RiyazIt.ttf
A	vendor/mpdf/mpdf/ttfonts/XW Zar Font Info.txt
A	vendor/mpdf/mpdf/ttfonts/ZawgyiOne.ttf
A	vendor/mpdf/mpdf/ttfonts/ayar.ttf
A	vendor/mpdf/mpdf/ttfonts/damase_v.2.ttf
A	vendor/mpdf/mpdf/ttfonts/kaputaunicode.ttf
A	vendor/mpdf/mpdf/ttfonts/lannaalif-v1-03.ttf
A	vendor/mpdf/mpdf/ttfonts/ocrb10.ttf
A	vendor/mpdf/mpdf/ttfonts/ocrbinfo.txt
A	vendor/setasign/fpdi/LICENSE
A	vendor/setasign/fpdi/README.md
A	vendor/setasign/fpdi/composer.json
A	vendor/setasign/fpdi/filters/FilterASCII85.php
A	vendor/setasign/fpdi/filters/FilterASCIIHexDecode.php
A	vendor/setasign/fpdi/filters/FilterLZW.php
A	vendor/setasign/fpdi/fpdf_tpl.php
A	vendor/setasign/fpdi/fpdi.php
A	vendor/setasign/fpdi/fpdi_bridge.php
A	vendor/setasign/fpdi/fpdi_pdf_parser.php
A	vendor/setasign/fpdi/pdf_context.php
A	vendor/setasign/fpdi/pdf_parser.php

commit b8f9753d37eff888eabc066d553cd11faf7fdf69	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Sat Jul 5 01:05:16 2025 +0100

    mudanças

M	BLL/Colaborador/BLL_ficha_colaborador.php
A	BLL/Colaborador/BLL_recibos_vencimento.php
D	BLL/Comuns/BLL_email.php
M	BLL/Comuns/BLL_forgot_password.php
M	BLL/Comuns/BLL_login.php
M	BLL/Comuns/BLL_notificacoes.php
M	BLL/Coordenador/BLL_dashboard_coordenador.php
M	BLL/RH/BLL_colaboradores_gerir.php
M	BLL/RH/BLL_dashboard_rh.php
A	BLL/RH/DAL_colaboradores_gerir.php
A	BLL/RH/ajax_onboarding_dados.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
A	DAL/Colaborador/DAL_recibos_vencimento.php
M	DAL/Comuns/DAL_login.php
M	DAL/Comuns/DAL_notificacoes.php
M	DAL/Comuns/DAL_perfil.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
M	DAL/RH/DAL_colaboradores_gerir.php
M	DAL/RH/DAL_dashboard_rh.php
D	Database/create_inscricao_formacoes.sql
D	Database/create_tables.sql
D	Database/fix_inscricoes_formacao.sql
M	UI/Colaborador/beneficios.php
A	UI/Colaborador/dashboard_colaborador.php
M	UI/Colaborador/ferias.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Colaborador/formacoes.php
M	UI/Colaborador/pagina_inicial_colaborador.php
M	UI/Colaborador/pedir_ferias.php
M	UI/Colaborador/recibos.php
M	UI/Comuns/login.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Convidado/onboarding_convidado.php
M	UI/Coordenador/dashboard_coordenador.php
A	UI/Coordenador/dashboard_coordenador_ajax.php
M	UI/Coordenador/equipa.php
M	UI/Coordenador/pagina_inicial_coordenador.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
A	UI/RH/dashboard_rh_ajax.php
M	UI/RH/equipa.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
M	UI/RH/gerir_beneficios.php
M	UI/RH/gerir_formacoes.php
A	UI/RH/recibos_submeter.php
M	UI/RH/relatorios.php
D	_doc/create_table_beneficios.sql
M	assets/CSS/Colaborador/ficha_colaborador.css
M	assets/CSS/Colaborador/pagina_inicial_colaborador.css
M	assets/CSS/Comuns/notificacoes.css
M	assets/CSS/Comuns/perfil.css
A	assets/CSS/Convidado/onboarding_convidado.css
M	assets/CSS/Coordenador/dashboard_coordenador.css
M	assets/CSS/RH/colaborador_novo.css
M	assets/CSS/RH/colaboradores_gerir.css
M	assets/CSS/RH/dashboard_rh.css
M	assets/CSS/RH/equipa_editar.css
M	assets/CSS/RH/equipas.css
M	assets/CSS/RH/gerir_recibos.css
M	assets/CSS/RH/pagina_inicial.css
A	assets/CSS/RH/recibos_submeter.css
M	composer.json
M	composer.lock
D	uploads/Recibos/1231247072025.pdf
A	uploads/Recibos/1231247_03_2025.pdf
A	uploads/Recibos/1231247_06_2021.pdf
M	vendor/autoload.php
A	vendor/bacon/bacon-qr-code/LICENSE
A	vendor/bacon/bacon-qr-code/README.md
A	vendor/bacon/bacon-qr-code/composer.json
A	vendor/bacon/bacon-qr-code/phpunit.xml.dist
A	vendor/bacon/bacon-qr-code/src/Common/BitArray.php
A	vendor/bacon/bacon-qr-code/src/Common/BitMatrix.php
A	vendor/bacon/bacon-qr-code/src/Common/BitUtils.php
A	vendor/bacon/bacon-qr-code/src/Common/CharacterSetEci.php
A	vendor/bacon/bacon-qr-code/src/Common/EcBlock.php
A	vendor/bacon/bacon-qr-code/src/Common/EcBlocks.php
A	vendor/bacon/bacon-qr-code/src/Common/ErrorCorrectionLevel.php
A	vendor/bacon/bacon-qr-code/src/Common/FormatInformation.php
A	vendor/bacon/bacon-qr-code/src/Common/Mode.php
A	vendor/bacon/bacon-qr-code/src/Common/ReedSolomonCodec.php
A	vendor/bacon/bacon-qr-code/src/Common/Version.php
A	vendor/bacon/bacon-qr-code/src/Encoder/BlockPair.php
A	vendor/bacon/bacon-qr-code/src/Encoder/ByteMatrix.php
A	vendor/bacon/bacon-qr-code/src/Encoder/Encoder.php
A	vendor/bacon/bacon-qr-code/src/Encoder/MaskUtil.php
A	vendor/bacon/bacon-qr-code/src/Encoder/MatrixUtil.php
A	vendor/bacon/bacon-qr-code/src/Encoder/QrCode.php
A	vendor/bacon/bacon-qr-code/src/Exception/ExceptionInterface.php
A	vendor/bacon/bacon-qr-code/src/Exception/InvalidArgumentException.php
A	vendor/bacon/bacon-qr-code/src/Exception/OutOfBoundsException.php
A	vendor/bacon/bacon-qr-code/src/Exception/RuntimeException.php
A	vendor/bacon/bacon-qr-code/src/Exception/UnexpectedValueException.php
A	vendor/bacon/bacon-qr-code/src/Exception/WriterException.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Color/Alpha.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Color/Cmyk.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Color/ColorInterface.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Color/Gray.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Color/Rgb.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Eye/CompositeEye.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Eye/EyeInterface.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Eye/ModuleEye.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Eye/SimpleCircleEye.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Eye/SquareEye.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Image/EpsImageBackEnd.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Image/ImageBackEndInterface.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Image/ImagickImageBackEnd.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Image/SvgImageBackEnd.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Image/TransformationMatrix.php
A	vendor/bacon/bacon-qr-code/src/Renderer/ImageRenderer.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Module/DotsModule.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Module/EdgeIterator/Edge.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Module/EdgeIterator/EdgeIterator.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Module/ModuleInterface.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Module/RoundnessModule.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Module/SquareModule.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Path/Close.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Path/Curve.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Path/EllipticArc.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Path/Line.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Path/Move.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Path/OperationInterface.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Path/Path.php
A	vendor/bacon/bacon-qr-code/src/Renderer/PlainTextRenderer.php
A	vendor/bacon/bacon-qr-code/src/Renderer/RendererInterface.php
A	vendor/bacon/bacon-qr-code/src/Renderer/RendererStyle/EyeFill.php
A	vendor/bacon/bacon-qr-code/src/Renderer/RendererStyle/Fill.php
A	vendor/bacon/bacon-qr-code/src/Renderer/RendererStyle/Gradient.php
A	vendor/bacon/bacon-qr-code/src/Renderer/RendererStyle/GradientType.php
A	vendor/bacon/bacon-qr-code/src/Renderer/RendererStyle/RendererStyle.php
A	vendor/bacon/bacon-qr-code/src/Writer.php
A	vendor/bacon/bacon-qr-code/test/Common/BitArrayTest.php
A	vendor/bacon/bacon-qr-code/test/Common/BitMatrixTest.php
A	vendor/bacon/bacon-qr-code/test/Common/BitUtilsTest.php
A	vendor/bacon/bacon-qr-code/test/Common/ErrorCorrectionLevelTest.php
A	vendor/bacon/bacon-qr-code/test/Common/FormatInformationTest.php
A	vendor/bacon/bacon-qr-code/test/Common/ModeTest.php
A	vendor/bacon/bacon-qr-code/test/Common/ReedSolomonCodecTest.php
A	vendor/bacon/bacon-qr-code/test/Common/VersionTest.php
A	vendor/bacon/bacon-qr-code/test/Encoder/EncoderTest.php
A	vendor/bacon/bacon-qr-code/test/Encoder/MaskUtilTest.php
A	vendor/bacon/bacon-qr-code/test/Encoder/MatrixUtilTest.php
A	vendor/bacon/bacon-qr-code/test/Integration/ImagickRenderingTest.php
A	vendor/bacon/bacon-qr-code/test/Integration/__snapshots__/files/ImagickRenderingTest__testGenericQrCode__1.png
A	vendor/bacon/bacon-qr-code/test/Integration/__snapshots__/files/ImagickRenderingTest__testIssue79__1.png
M	vendor/composer/autoload_psr4.php
M	vendor/composer/autoload_real.php
M	vendor/composer/autoload_static.php
M	vendor/composer/installed.json
M	vendor/composer/installed.php
M	vendor/composer/platform_check.php
A	vendor/dasprid/enum/LICENSE
A	vendor/dasprid/enum/README.md
A	vendor/dasprid/enum/composer.json
A	vendor/dasprid/enum/src/AbstractEnum.php
A	vendor/dasprid/enum/src/EnumMap.php
A	vendor/dasprid/enum/src/Exception/CloneNotSupportedException.php
A	vendor/dasprid/enum/src/Exception/ExceptionInterface.php
A	vendor/dasprid/enum/src/Exception/ExpectationException.php
A	vendor/dasprid/enum/src/Exception/IllegalArgumentException.php
A	vendor/dasprid/enum/src/Exception/MismatchException.php
A	vendor/dasprid/enum/src/Exception/SerializeNotSupportedException.php
A	vendor/dasprid/enum/src/Exception/UnserializeNotSupportedException.php
A	vendor/dasprid/enum/src/NullValue.php
A	vendor/paragonie/constant_time_encoding/LICENSE.txt
A	vendor/paragonie/constant_time_encoding/README.md
A	vendor/paragonie/constant_time_encoding/composer.json
A	vendor/paragonie/constant_time_encoding/src/Base32.php
A	vendor/paragonie/constant_time_encoding/src/Base32Hex.php
A	vendor/paragonie/constant_time_encoding/src/Base64.php
A	vendor/paragonie/constant_time_encoding/src/Base64DotSlash.php
A	vendor/paragonie/constant_time_encoding/src/Base64DotSlashOrdered.php
A	vendor/paragonie/constant_time_encoding/src/Base64UrlSafe.php
A	vendor/paragonie/constant_time_encoding/src/Binary.php
A	vendor/paragonie/constant_time_encoding/src/EncoderInterface.php
A	vendor/paragonie/constant_time_encoding/src/Encoding.php
A	vendor/paragonie/constant_time_encoding/src/Hex.php
A	vendor/paragonie/constant_time_encoding/src/RFC4648.php
A	vendor/phpmailer/phpmailer/COMMITMENT
A	vendor/phpmailer/phpmailer/LICENSE
A	vendor/phpmailer/phpmailer/README.md
A	vendor/phpmailer/phpmailer/SECURITY.md
A	vendor/phpmailer/phpmailer/SMTPUTF8.md
A	vendor/phpmailer/phpmailer/VERSION
A	vendor/phpmailer/phpmailer/composer.json
A	vendor/phpmailer/phpmailer/get_oauth_token.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-af.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ar.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-as.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-az.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ba.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-be.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-bg.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-bn.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ca.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-cs.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-da.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-de.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-el.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-eo.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-es.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-et.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-fa.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-fi.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-fo.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-fr.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-gl.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-he.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-hi.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-hr.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-hu.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-hy.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-id.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-it.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ja.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ka.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ko.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ku.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-lt.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-lv.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-mg.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-mn.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ms.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-nb.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-nl.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-pl.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-pt.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-pt_br.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ro.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ru.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-si.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-sk.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-sl.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-sr.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-sr_latn.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-sv.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-tl.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-tr.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-uk.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ur.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-vi.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-zh.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-zh_cn.php
A	vendor/phpmailer/phpmailer/src/DSNConfigurator.php
A	vendor/phpmailer/phpmailer/src/Exception.php
A	vendor/phpmailer/phpmailer/src/OAuth.php
A	vendor/phpmailer/phpmailer/src/OAuthTokenProvider.php
A	vendor/phpmailer/phpmailer/src/PHPMailer.php
A	vendor/phpmailer/phpmailer/src/POP3.php
A	vendor/phpmailer/phpmailer/src/SMTP.php
A	vendor/pragmarx/google2fa/.github/workflows/run-tests.yml
A	vendor/pragmarx/google2fa/CHANGELOG.md
A	vendor/pragmarx/google2fa/LICENSE.md
A	vendor/pragmarx/google2fa/README.md
A	vendor/pragmarx/google2fa/composer.json
A	vendor/pragmarx/google2fa/src/Exceptions/Contracts/Google2FA.php
A	vendor/pragmarx/google2fa/src/Exceptions/Contracts/IncompatibleWithGoogleAuthenticator.php
A	vendor/pragmarx/google2fa/src/Exceptions/Contracts/InvalidAlgorithm.php
A	vendor/pragmarx/google2fa/src/Exceptions/Contracts/InvalidCharacters.php
A	vendor/pragmarx/google2fa/src/Exceptions/Contracts/SecretKeyTooShort.php
A	vendor/pragmarx/google2fa/src/Exceptions/Google2FAException.php
A	vendor/pragmarx/google2fa/src/Exceptions/IncompatibleWithGoogleAuthenticatorException.php
A	vendor/pragmarx/google2fa/src/Exceptions/InvalidAlgorithmException.php
A	vendor/pragmarx/google2fa/src/Exceptions/InvalidCharactersException.php
A	vendor/pragmarx/google2fa/src/Exceptions/SecretKeyTooShortException.php
A	vendor/pragmarx/google2fa/src/Google2FA.php
A	vendor/pragmarx/google2fa/src/Support/Base32.php
A	vendor/pragmarx/google2fa/src/Support/Constants.php
A	vendor/pragmarx/google2fa/src/Support/QRCode.php

commit 8153e81ebf87a0d11cd93c9e426ece97003486e2	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Fri Jul 4 10:51:24 2025 +0100

    chatbot

M	UI/Colaborador/beneficios.php
D	UI/Colaborador/dashboard_colaborador.php
M	UI/Colaborador/ferias.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Colaborador/formacoes.php
M	UI/Colaborador/pagina_inicial_colaborador.php
M	UI/Colaborador/pedir_ferias.php
M	UI/Colaborador/recibos.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Coordenador/pagina_inicial_coordenador.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipa.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
M	UI/RH/gerir_beneficios.php
M	UI/RH/gerir_formacoes.php
M	UI/RH/relatorios.php
A	uploads/Recibos/1231247072025.pdf

commit d44cbfae7b68e20d5087a5a1e5c3db4ab4a6bc7e	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Fri Jul 4 09:08:38 2025 +0100

    recibos

D	BLL/Colaborador/BLL_recibos_vencimento.php
M	BLL/RH/BLL_equipa_editar.php
A	BLL/RH/BLL_recibos_vencimento.php
D	DAL/Colaborador/DAL_recibos_vencimento.php
M	DAL/RH/DAL_equipa_editar.php
A	DAL/RH/DAL_recibos_vencimento.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Colaborador/pagina_inicial_colaborador.php
M	UI/Colaborador/recibos.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Coordenador/pagina_inicial_coordenador.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipa.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
M	UI/RH/gerir_beneficios.php
M	UI/RH/gerir_formacoes.php
A	UI/RH/gerir_recibos.php
M	UI/RH/pagina_inicial_RH.php
D	UI/RH/recibos_submeter.php
M	UI/RH/relatorios.php
M	assets/CSS/Colaborador/ficha_colaborador.css
M	assets/CSS/Coordenador/relatorios_equipa.css
A	assets/CSS/RH/gerir_recibos.css
A	uploads/Recibos/1231247062025.pdf

commit 814311fc3f590cb4250c59f687b55f64751d6b8f	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Fri Jul 4 00:06:32 2025 +0100

    rh perfil completo
    
    apenas faltam alguns estilos e a pagina dos recibos

M	BLL/Coordenador/BLL_dashboard_coordenador.php
A	BLL/RH/BLL_formacoes_gerir.php
A	BLL/RH/BLL_gerir_beneficios.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
A	DAL/RH/DAL_gerir_beneficios.php
A	DAL/RH/DAL_gerir_formacoes.php
M	UI/Colaborador/beneficios.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Coordenador/dashboard_coordenador.php
M	UI/Coordenador/equipa.php
M	UI/Coordenador/pagina_inicial_coordenador.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipa.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
A	UI/RH/gerir_beneficios.php
A	UI/RH/gerir_formacoes.php
M	UI/RH/pagina_inicial_RH.php
M	UI/RH/relatorios.php
A	_doc/create_table_beneficios.sql
M	assets/CSS/Comuns/notificacoes.css
M	assets/CSS/Comuns/perfil.css
M	assets/CSS/Coordenador/dashboard_coordenador.css
M	assets/CSS/RH/colaborador_novo.css
M	assets/CSS/RH/colaboradores_gerir.css
M	assets/CSS/RH/dashboard_rh.css
M	assets/CSS/RH/equipa_editar.css
M	assets/CSS/RH/equipa_nova.css
M	assets/CSS/RH/equipas.css
M	assets/CSS/RH/exportar.css
A	assets/CSS/RH/gerir_beneficios.css
A	assets/CSS/RH/gerir_formacoes.css
M	assets/CSS/RH/pagina_inicial.css
D	assets/CSS/RH/pagina_inicial_RH.css
M	assets/CSS/RH/relatorios.css

commit 87b8b2ede9db2c81b59e6343a0490b48cd975384	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Thu Jul 3 21:55:06 2025 +0100

    rh

M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipa.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
M	UI/RH/relatorios.php
M	assets/CSS/RH/colaboradores_gerir.css
M	assets/CSS/RH/dashboard_rh.css
M	assets/CSS/RH/equipa_editar.css
M	assets/CSS/RH/equipas.css
M	assets/CSS/RH/exportar.css
M	assets/CSS/RH/relatorios.css

commit eea6a1886c3a7e773e28af9f273c441ced746b20	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Thu Jul 3 20:17:32 2025 +0100

    Dashboard Finalizada - RH

M	BLL/RH/BLL_dashboard_rh.php
M	DAL/RH/DAL_dashboard_rh.php
M	UI/RH/dashboard_rh.php

commit a4c3a3b7204b20684e74ec3f10669ece0aecc43c	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Thu Jul 3 20:01:18 2025 +0100

    DASHBOARD

M	BLL/RH/BLL_dashboard_rh.php
M	BLL/RH/BLL_exportar.php
M	DAL/RH/DAL_dashboard_rh.php
M	DAL/RH/DAL_exportar.php
M	UI/Colaborador/beneficios.php
M	UI/Colaborador/ferias.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Comuns/notificacoes.php
M	UI/RH/dashboard_rh.php
M	UI/RH/exportar.php
M	assets/CSS/RH/dashboard_rh.css

commit 1a1c92434249b611fe92d3d176962c7e5456586a	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Thu Jul 3 18:01:03 2025 +0100

    mudanças RH

M	BLL/Colaborador/BLL_ficha_colaborador.php
M	BLL/Colaborador/BLL_inscricoes.php
A	BLL/Comuns/BLL_email.php
M	BLL/Comuns/BLL_perfil.php
M	BLL/RH/BLL_colaboradores_gerir.php
M	BLL/RH/BLL_dashboard_rh.php
A	BLL/RH/BLL_recibos.php
M	DAL/Colaborador/DAL_ferias.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
M	DAL/RH/DAL_colaboradores_gerir.php
M	DAL/RH/DAL_dashboard_rh.php
A	DAL/RH/DAL_recibos.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipa.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
M	UI/RH/pagina_inicial_RH.php
A	UI/RH/recibos_submeter.php
M	UI/RH/relatorios.php
M	assets/CSS/Colaborador/ficha_colaborador.css
M	assets/CSS/Comuns/notificacoes.css
M	assets/CSS/Comuns/perfil.css
M	assets/CSS/RH/colaborador_novo.css
M	assets/CSS/RH/colaboradores_gerir.css
M	assets/CSS/RH/dashboard_rh.css
M	assets/CSS/RH/equipa_editar.css
M	assets/CSS/RH/equipa_nova.css
M	assets/CSS/RH/equipas.css
M	assets/CSS/RH/exportar.css
M	assets/CSS/RH/pagina_inicial.css
A	assets/CSS/RH/pagina_inicial_RH.css
M	assets/CSS/RH/relatorios.css
A	uploads/Recibos/recibo_6_2025_6_1751543154.pdf
A	uploads/Recibos/recibo_6_2025_6_1751543237.pdf

commit 1d723a4f9eebb4fa87f3440f6a7d3059133cf5fa	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Thu Jul 3 16:34:21 2025 +0100

    mudanças

M	BLL/RH/BLL_dashboard_rh.php
M	DAL/RH/DAL_dashboard_rh.php
M	UI/RH/dashboard_rh.php
M	assets/CSS/RH/dashboard_rh.css

commit 5598f42e63d38d27851ca1804006aa3fe7801416	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Thu Jul 3 13:04:14 2025 +0100

    alterações

M	BLL/Colaborador/BLL_ficha_colaborador.php
M	BLL/Colaborador/BLL_formacoes.php
M	BLL/Comuns/BLL_notificacoes.php
M	BLL/Comuns/BLL_perfil.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
M	DAL/Colaborador/DAL_formacoes.php
M	DAL/Comuns/DAL_perfil.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Colaborador/pedir_ferias.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	assets/CSS/Colaborador/ficha_colaborador.css
M	assets/CSS/Colaborador/pagina_inicial_colaborador.css
M	assets/CSS/Comuns/perfil.css
A	uploads/comprovativos/comprovativo_cc_8_1751542638.pdf
A	uploads/comprovativos/comprovativo_cc_8_1751543005.pdf
A	uploads/comprovativos/comprovativo_cc_8_1751543339.pdf
A	uploads/comprovativos/comprovativo_cc_8_1751543406.pdf

commit f3bccc0e7e0efbba05e54c379eb531107a90de9e	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Thu Jul 3 01:51:58 2025 +0100

    colaborador
    
    ficheiros atualizados

M	BLL/Admin/BLL_alerta_novo.php
M	BLL/Admin/BLL_alertas.php
M	BLL/Colaborador/BLL_ficha_colaborador.php
A	BLL/Colaborador/BLL_formacoes.php
M	BLL/Comuns/BLL_notificacoes.php
M	BLL/Coordenador/BLL_dashboard_coordenador.php
M	BLL/RH/BLL_dashboard_rh.php
M	BLL/RH/BLL_equipa_editar.php
M	BLL/RH/BLL_equipa_nova.php
M	DAL/Admin/DAL_alertas.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
A	DAL/Colaborador/DAL_formacoes.php
M	DAL/Colaborador/DAL_inscricoes.php
M	DAL/Comuns/DAL_notificacoes.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
M	DAL/RH/DAL_colaboradores_gerir.php
M	DAL/RH/DAL_dashboard_rh.php
M	DAL/RH/DAL_equipa_editar.php
M	DAL/RH/DAL_equipa_nova.php
M	DAL/RH/DAL_equipas.php
A	Database/create_inscricao_formacoes.sql
A	Database/create_tables.sql
A	Database/fix_inscricoes_formacao.sql
M	UI/Admin/alerta_novo.php
M	UI/Colaborador/beneficios.php
M	UI/Colaborador/ferias.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Colaborador/formacoes.php
M	UI/Colaborador/inscrever_formacao.php
M	UI/Colaborador/pagina_inicial_colaborador.php
M	UI/Colaborador/pedir_ferias.php
M	UI/Colaborador/recibos.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Coordenador/equipa.php
M	UI/Coordenador/pagina_inicial_coordenador.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipa.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
M	assets/CSS/Colaborador/beneficios.css
M	assets/CSS/Colaborador/ferias.css
M	assets/CSS/Colaborador/ficha_colaborador.css
M	assets/CSS/Colaborador/formacoes.css
M	assets/CSS/Colaborador/recibos_vencimento.css
M	assets/CSS/Comuns/notificacoes.css
M	assets/CSS/Comuns/perfil.css
M	assets/CSS/Coordenador/equipa.css
M	assets/CSS/RH/dashboard_rh.css
M	vendor/composer/installed.php

commit 917f3dbc36fb61051621d124ed6c1d38e706e54c	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Wed Jul 2 08:31:47 2025 +0100

    mudanças
    
    mensagem equipa

M	UI/Coordenador/equipa.php
M	assets/CSS/Coordenador/equipa.css

commit c534aec05abd0e0be9600a042c1c41a763605627	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Tue Jul 1 19:13:50 2025 +0100

    pagina perfil
    
    novo estilo e possibilidade de alterar password

M	BLL/Comuns/BLL_perfil.php
M	DAL/Comuns/DAL_perfil.php
A	UI/Comuns/alterar_password.php
M	UI/Comuns/perfil.php
M	assets/CSS/Comuns/perfil.css

commit 053fdc87ab3b180dfd513b7914368a5578c040e3	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Tue Jul 1 18:42:26 2025 +0100

    criacao de paginas
    
    ferias, beneficios, formações e recibos de vencimento

A	BLL/Colaborador/BLL_ferias.php
A	BLL/Colaborador/BLL_inscricoes.php
A	DAL/Colaborador/DAL_ferias.php
A	DAL/Colaborador/DAL_inscricoes.php
A	UI/Colaborador/beneficios.php
A	UI/Colaborador/ferias.php
M	UI/Colaborador/formacoes.php
A	UI/Colaborador/inscrever_formacao.php
A	UI/Colaborador/pedir_ferias.php
M	UI/Colaborador/recibos.php
A	assets/CSS/Colaborador/beneficios.css
A	assets/CSS/Colaborador/ferias.css
M	assets/CSS/Colaborador/formacoes.css

commit 4e3b65ca4f8396701123b06e38eff415d62e0612	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Tue Jul 1 17:15:46 2025 +0100

    novas paginas
    
    recibos de vencimento e formações

A	BLL/Admin/BLL_alerta_novo.php
M	BLL/Authenticator.php
M	BLL/Colaborador/BLL_ficha_colaborador.php
A	BLL/Colaborador/BLL_recibos_vencimento.php
A	BLL/Comuns/BLL_forgot_password.php
M	BLL/Comuns/BLL_notificacoes.php
A	BLL/RH/BLL_equipa_editar.php
A	BLL/RH/BLL_equipa_nova.php
M	BLL/RH/BLL_equipas.php
A	DAL/Admin/DAL_alerta_novo.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
A	DAL/Colaborador/DAL_recibos_vencimento.php
A	DAL/Comuns/DAL_forgot_password.php
M	DAL/Comuns/DAL_notificacoes.php
A	DAL/RH/DAL_equipa_editar.php
A	DAL/RH/DAL_equipa_nova.php
M	DAL/RH/DAL_equipas.php
M	GitAnalysis/Sprint_1/Commits.md
M	GitAnalysis/Sprint_1/Contributions.md
A	GitAnalysis/Sprint_2/Commits.md
A	GitAnalysis/Sprint_2/Contributions.md
A	UI/Admin/alerta_novo.php
M	UI/Colaborador/ficha_colaborador.php
A	UI/Colaborador/formacoes.php
M	UI/Colaborador/pagina_inicial_colaborador.php
A	UI/Colaborador/recibos.php
A	UI/Comuns/forgot_password.php
M	UI/Comuns/notificacoes.php
M	UI/Coordenador/equipa.php
M	UI/Coordenador/pagina_inicial_coordenador.php
M	UI/RH/dashboard_rh.php
A	UI/RH/equipa.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
A	assets/CSS/Admin/alerta_novo.css
A	assets/CSS/Colaborador/formacoes.css
M	assets/CSS/Colaborador/pagina_inicial_colaborador.css
A	assets/CSS/Colaborador/recibos_vencimento.css
A	assets/CSS/Comuns/forgot_password.css
A	assets/CSS/RH/equipa_editar.css
M	assets/CSS/RH/equipa_nova.css
A	composer.json
A	composer.lock
A	uploads/comprovativos/comprovativo_10_1750770302.pdf
A	uploads/comprovativos/comprovativo_8_1750766087.pdf
A	uploads/comprovativos/comprovativo_8_1750766690.pdf
A	uploads/comprovativos/comprovativo_8_1750767197.pdf
A	uploads/comprovativos/comprovativo_cartao_continente_15_1750843752.pdf
A	uploads/comprovativos/comprovativo_cc_15_1750843752.pdf
A	uploads/comprovativos/comprovativo_estado_civil_15_1750843752.pdf
A	uploads/comprovativos/comprovativo_iban_15_1750843752.pdf
A	vendor/autoload.php
A	vendor/composer/ClassLoader.php
A	vendor/composer/InstalledVersions.php
A	vendor/composer/LICENSE
A	vendor/composer/autoload_classmap.php
A	vendor/composer/autoload_namespaces.php
A	vendor/composer/autoload_psr4.php
A	vendor/composer/autoload_real.php
A	vendor/composer/autoload_static.php
A	vendor/composer/installed.json
A	vendor/composer/installed.php
A	vendor/composer/platform_check.php
</pre>

</details>

#### 1231247@isep.ipp.pt
<details>
<summary>Diff</summary>

<pre>
 0 files changed
</pre>
</details>
<details>
<summary>Commits</summary>

<pre>
commit 7ca839a8a8ddaf9666abc895223d6ae59171ae64	refs/remotes/origin/bruno_branch (origin/bruno_branch)
Author: Bruno Costa <1231247@isep.ipp.pt>
Date:   Sun Jul 6 00:21:13 2025 +0100

    1

M	UI/RH/relatorios_ajax.php
M	UI/RH/relatorios_pdf.php

commit 89045ebaeddc787bd734ff472725c42ddea5dead	refs/remotes/origin/bruno_branch
Author: Bruno Costa <1231247@isep.ipp.pt>
Date:   Sat Jul 5 21:54:11 2025 +0100

    1

A	_doc/BLL/Admin/BLL_alerta_novo.php
A	_doc/BLL/Admin/BLL_alertas.php
A	_doc/BLL/Admin/BLL_campos_personalizados.php
A	_doc/BLL/Admin/BLL_dashboard_admin.php
A	_doc/BLL/Admin/BLL_permissoes.php
A	_doc/BLL/Admin/BLL_utilizadores.php
A	_doc/BLL/Authenticator.php
A	_doc/BLL/Colaborador/BLL_dashboard_colaborador.php
A	_doc/BLL/Colaborador/BLL_ferias.php
A	_doc/BLL/Colaborador/BLL_ficha_colaborador.php
A	_doc/BLL/Colaborador/BLL_formacoes.php
A	_doc/BLL/Colaborador/BLL_inscricoes.php
A	_doc/BLL/Colaborador/BLL_recibos_vencimento.php
A	_doc/BLL/Comuns/BLL_erro.php
A	_doc/BLL/Comuns/BLL_forgot_password.php
A	_doc/BLL/Comuns/BLL_login.php
A	_doc/BLL/Comuns/BLL_mensagens.php
A	_doc/BLL/Comuns/BLL_notificacoes.php
A	_doc/BLL/Comuns/BLL_perfil.php
A	_doc/BLL/Convidado/BLL_dashboard_convidado.php
A	_doc/BLL/Convidado/BLL_onboarding_convidado.php
A	_doc/BLL/Coordenador/BLL_dashboard_coordenador.php
A	_doc/BLL/RH/BLL_campos_personalizados.php
A	_doc/BLL/RH/BLL_colaboradores_gerir.php
A	_doc/BLL/RH/BLL_dashboard_rh.php
A	_doc/BLL/RH/BLL_equipa_editar.php
A	_doc/BLL/RH/BLL_equipa_nova.php
A	_doc/BLL/RH/BLL_equipas.php
A	_doc/BLL/RH/BLL_exportar.php
A	_doc/BLL/RH/BLL_formacoes_gerir.php
A	_doc/BLL/RH/BLL_gerir_beneficios.php
A	_doc/BLL/RH/BLL_recibos.php
A	_doc/BLL/RH/BLL_recibos_vencimento.php
A	_doc/BLL/RH/BLL_relatorios.php
A	_doc/BLL/RH/DAL_colaboradores_gerir.php
A	_doc/BLL/RH/ajax_onboarding_dados.php
A	_doc/DAL/Admin/DAL_alerta_novo.php
A	_doc/DAL/Admin/DAL_alertas.php
A	_doc/DAL/Admin/DAL_campos_personalizados.php
A	_doc/DAL/Admin/DAL_dashboard_admin.php
A	_doc/DAL/Admin/DAL_permissoes.php
A	_doc/DAL/Admin/DAL_utilizadores.php
A	_doc/DAL/Colaborador/DAL_dashboard_colaborador.php
A	_doc/DAL/Colaborador/DAL_ferias.php
A	_doc/DAL/Colaborador/DAL_ficha_colaborador.php
A	_doc/DAL/Colaborador/DAL_formacoes.php
A	_doc/DAL/Colaborador/DAL_inscricoes.php
A	_doc/DAL/Colaborador/DAL_recibos_vencimento.php
A	_doc/DAL/Comuns/DAL_erro.php
A	_doc/DAL/Comuns/DAL_forgot_password.php
A	_doc/DAL/Comuns/DAL_login.php
A	_doc/DAL/Comuns/DAL_mensagens.php
A	_doc/DAL/Comuns/DAL_notificacoes.php
A	_doc/DAL/Comuns/DAL_perfil.php
A	_doc/DAL/Convidado/DAL_dashboard_convidado.php
A	_doc/DAL/Convidado/DAL_onboarding_convidado.php
A	_doc/DAL/Coordenador/DAL_dashboard_coordenador.php
A	_doc/DAL/Database.php
A	_doc/DAL/RH/DAL_campos_personalizados.php
A	_doc/DAL/RH/DAL_colaboradores_gerir.php
A	_doc/DAL/RH/DAL_dashboard_rh.php
A	_doc/DAL/RH/DAL_equipa_editar.php
A	_doc/DAL/RH/DAL_equipa_nova.php
A	_doc/DAL/RH/DAL_equipas.php
A	_doc/DAL/RH/DAL_exportar.php
A	_doc/DAL/RH/DAL_gerir_beneficios.php
A	_doc/DAL/RH/DAL_gerir_formacoes.php
A	_doc/DAL/RH/DAL_recibos.php
A	_doc/DAL/RH/DAL_recibos_vencimento.php
A	_doc/DAL/RH/DAL_relatorios.php
A	_doc/DAL/UserDataAcess.php
A	_doc/GitAnalysis/Sprint_1/Commits.md
A	_doc/GitAnalysis/Sprint_1/Contributions.md
A	_doc/GitAnalysis/Sprint_2/Commits.md
A	_doc/GitAnalysis/Sprint_2/Contributions.md
A	_doc/UI/Admin/alerta_novo.php
A	_doc/UI/Admin/alertas.php
A	_doc/UI/Admin/campos_personalizados.php
A	_doc/UI/Admin/dashboard_admin.php
A	_doc/UI/Admin/pagina_inicial_admin.php
A	_doc/UI/Admin/permissoes.php
A	_doc/UI/Admin/utilizador_editar.php
A	_doc/UI/Admin/utilizador_novo.php
A	_doc/UI/Admin/utilizador_remover.php
A	_doc/UI/Admin/utilizadores.php
A	_doc/UI/Colaborador/beneficios.php
A	_doc/UI/Colaborador/dashboard_colaborador.php
A	_doc/UI/Colaborador/ferias.php
A	_doc/UI/Colaborador/ficha_colaborador.php
A	_doc/UI/Colaborador/formacoes.php
A	_doc/UI/Colaborador/inscrever_formacao.php
A	_doc/UI/Colaborador/pagina_inicial_colaborador.php
A	_doc/UI/Colaborador/pedir_ferias.php
A	_doc/UI/Colaborador/recibos.php
A	_doc/UI/Comuns/alterar_password.php
A	_doc/UI/Comuns/enviar_mensagem.php
A	_doc/UI/Comuns/erro.php
A	_doc/UI/Comuns/forgot_password.php
A	_doc/UI/Comuns/login.php
A	_doc/UI/Comuns/logout.php
A	_doc/UI/Comuns/notificacoes.php
A	_doc/UI/Comuns/perfil.php
A	_doc/UI/Convidado/dashboard_convidado.php
A	_doc/UI/Convidado/onboarding_convidado.php
A	_doc/UI/Coordenador/dashboard_coordenador.php
A	_doc/UI/Coordenador/dashboard_coordenador_ajax.php
A	_doc/UI/Coordenador/equipa.php
A	_doc/UI/Coordenador/pagina_inicial_coordenador.php
A	_doc/UI/Coordenador/relatorios_equipa.php
A	_doc/UI/RH/campos_personalizados.php
A	_doc/UI/RH/colaborador_novo.php
A	_doc/UI/RH/colaboradores_gerir.php
A	_doc/UI/RH/dashboard_rh.php
A	_doc/UI/RH/dashboard_rh_ajax.php
A	_doc/UI/RH/equipa.php
A	_doc/UI/RH/equipa_nova.php
A	_doc/UI/RH/equipas.php
A	_doc/UI/RH/exportar.php
A	_doc/UI/RH/gerir_beneficios.php
A	_doc/UI/RH/gerir_formacoes.php
A	_doc/UI/RH/gerir_recibos.php
A	_doc/UI/RH/pagina_inicial_RH.php
A	_doc/UI/RH/recibos_submeter.php
A	_doc/UI/RH/relatorios.php
A	_doc/UI/RH/relatorios_ajax.php
A	_doc/UI/RH/relatorios_pdf.php
A	_doc/assets/1.png
A	_doc/assets/2.png
A	_doc/assets/3.png
A	_doc/assets/4.png
A	_doc/assets/5.png
A	_doc/assets/6.png
A	_doc/assets/CSS/Admin/alerta_novo.css
A	_doc/assets/CSS/Admin/alertas.css
A	_doc/assets/CSS/Admin/base.css
A	_doc/assets/CSS/Admin/campos.css
A	_doc/assets/CSS/Admin/dashboard.css
A	_doc/assets/CSS/Admin/utilizadores.css
A	_doc/assets/CSS/Colaborador/beneficios.css
A	_doc/assets/CSS/Colaborador/dashboard_colaborador.css
A	_doc/assets/CSS/Colaborador/ferias.css
A	_doc/assets/CSS/Colaborador/ficha_colaborador.css
A	_doc/assets/CSS/Colaborador/formacoes.css
A	_doc/assets/CSS/Colaborador/pagina_inicial_colaborador.css
A	_doc/assets/CSS/Colaborador/recibos_vencimento.css
A	_doc/assets/CSS/Comuns/erro.css
A	_doc/assets/CSS/Comuns/forgot_password.css
A	_doc/assets/CSS/Comuns/login.css
A	_doc/assets/CSS/Comuns/logout.css
A	_doc/assets/CSS/Comuns/notificacoes.css
A	_doc/assets/CSS/Comuns/perfil.css
A	_doc/assets/CSS/Convidado/onboarding_convidado.css
A	_doc/assets/CSS/Coordenador/dashboard_coordenador.css
A	_doc/assets/CSS/Coordenador/equipa.css
A	_doc/assets/CSS/Coordenador/pagina_inicial_coordenador.css
A	_doc/assets/CSS/Coordenador/relatorios_equipa.css
A	_doc/assets/CSS/RH/campos_personalizados.css
A	_doc/assets/CSS/RH/colaborador_novo.css
A	_doc/assets/CSS/RH/colaboradores_gerir.css
A	_doc/assets/CSS/RH/dashboard_rh.css
A	_doc/assets/CSS/RH/equipa_editar.css
A	_doc/assets/CSS/RH/equipa_nova.css
A	_doc/assets/CSS/RH/equipas.css
A	_doc/assets/CSS/RH/exportar.css
A	_doc/assets/CSS/RH/gerir_beneficios.css
A	_doc/assets/CSS/RH/gerir_formacoes.css
A	_doc/assets/CSS/RH/gerir_recibos.css
A	_doc/assets/CSS/RH/pagina_inicial.css
A	_doc/assets/CSS/RH/recibos_submeter.css
A	_doc/assets/CSS/RH/relatorios.css
A	_doc/assets/chatbot.js
A	_doc/assets/fundo.png
A	_doc/assets/script.js
A	_doc/assets/tlantic-logo-escuro.png
A	_doc/assets/tlantic-logo.png
A	_doc/assets/tlantic-logo2.png
A	_doc/composer.json
A	_doc/composer.lock
A	_doc/index.php
A	_doc/uploads/Recibos/1231247062025.pdf
A	_doc/uploads/Recibos/1231247_03_2025.pdf
A	_doc/uploads/Recibos/1231247_06_2021.pdf
A	_doc/uploads/Recibos/recibo_6_2025_6_1751543154.pdf
A	_doc/uploads/Recibos/recibo_6_2025_6_1751543237.pdf
A	_doc/uploads/comprovativos/comprovativo_10_1750770302.pdf
A	_doc/uploads/comprovativos/comprovativo_8_1750766087.pdf
A	_doc/uploads/comprovativos/comprovativo_8_1750766690.pdf
A	_doc/uploads/comprovativos/comprovativo_8_1750767197.pdf
A	_doc/uploads/comprovativos/comprovativo_cartao_continente_15_1750843752.pdf
A	_doc/uploads/comprovativos/comprovativo_cc_15_1750843752.pdf
A	_doc/uploads/comprovativos/comprovativo_cc_8_1751542638.pdf
A	_doc/uploads/comprovativos/comprovativo_cc_8_1751543005.pdf
A	_doc/uploads/comprovativos/comprovativo_cc_8_1751543339.pdf
A	_doc/uploads/comprovativos/comprovativo_cc_8_1751543406.pdf
A	_doc/uploads/comprovativos/comprovativo_estado_civil_15_1750843752.pdf
A	_doc/uploads/comprovativos/comprovativo_iban_15_1750843752.pdf
A	_doc/vendor/autoload.php
A	_doc/vendor/bacon/bacon-qr-code/LICENSE
A	_doc/vendor/bacon/bacon-qr-code/README.md
A	_doc/vendor/bacon/bacon-qr-code/composer.json
A	_doc/vendor/bacon/bacon-qr-code/phpunit.xml.dist
A	_doc/vendor/bacon/bacon-qr-code/src/Common/BitArray.php
A	_doc/vendor/bacon/bacon-qr-code/src/Common/BitMatrix.php
A	_doc/vendor/bacon/bacon-qr-code/src/Common/BitUtils.php
A	_doc/vendor/bacon/bacon-qr-code/src/Common/CharacterSetEci.php
A	_doc/vendor/bacon/bacon-qr-code/src/Common/EcBlock.php
A	_doc/vendor/bacon/bacon-qr-code/src/Common/EcBlocks.php
A	_doc/vendor/bacon/bacon-qr-code/src/Common/ErrorCorrectionLevel.php
A	_doc/vendor/bacon/bacon-qr-code/src/Common/FormatInformation.php
A	_doc/vendor/bacon/bacon-qr-code/src/Common/Mode.php
A	_doc/vendor/bacon/bacon-qr-code/src/Common/ReedSolomonCodec.php
A	_doc/vendor/bacon/bacon-qr-code/src/Common/Version.php
A	_doc/vendor/bacon/bacon-qr-code/src/Encoder/BlockPair.php
A	_doc/vendor/bacon/bacon-qr-code/src/Encoder/ByteMatrix.php
A	_doc/vendor/bacon/bacon-qr-code/src/Encoder/Encoder.php
A	_doc/vendor/bacon/bacon-qr-code/src/Encoder/MaskUtil.php
A	_doc/vendor/bacon/bacon-qr-code/src/Encoder/MatrixUtil.php
A	_doc/vendor/bacon/bacon-qr-code/src/Encoder/QrCode.php
A	_doc/vendor/bacon/bacon-qr-code/src/Exception/ExceptionInterface.php
A	_doc/vendor/bacon/bacon-qr-code/src/Exception/InvalidArgumentException.php
A	_doc/vendor/bacon/bacon-qr-code/src/Exception/OutOfBoundsException.php
A	_doc/vendor/bacon/bacon-qr-code/src/Exception/RuntimeException.php
A	_doc/vendor/bacon/bacon-qr-code/src/Exception/UnexpectedValueException.php
A	_doc/vendor/bacon/bacon-qr-code/src/Exception/WriterException.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Color/Alpha.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Color/Cmyk.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Color/ColorInterface.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Color/Gray.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Color/Rgb.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Eye/CompositeEye.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Eye/EyeInterface.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Eye/ModuleEye.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Eye/SimpleCircleEye.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Eye/SquareEye.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Image/EpsImageBackEnd.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Image/ImageBackEndInterface.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Image/ImagickImageBackEnd.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Image/SvgImageBackEnd.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Image/TransformationMatrix.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/ImageRenderer.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Module/DotsModule.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Module/EdgeIterator/Edge.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Module/EdgeIterator/EdgeIterator.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Module/ModuleInterface.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Module/RoundnessModule.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Module/SquareModule.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Path/Close.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Path/Curve.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Path/EllipticArc.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Path/Line.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Path/Move.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Path/OperationInterface.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/Path/Path.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/PlainTextRenderer.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/RendererInterface.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/RendererStyle/EyeFill.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/RendererStyle/Fill.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/RendererStyle/Gradient.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/RendererStyle/GradientType.php
A	_doc/vendor/bacon/bacon-qr-code/src/Renderer/RendererStyle/RendererStyle.php
A	_doc/vendor/bacon/bacon-qr-code/src/Writer.php
A	_doc/vendor/bacon/bacon-qr-code/test/Common/BitArrayTest.php
A	_doc/vendor/bacon/bacon-qr-code/test/Common/BitMatrixTest.php
A	_doc/vendor/bacon/bacon-qr-code/test/Common/BitUtilsTest.php
A	_doc/vendor/bacon/bacon-qr-code/test/Common/ErrorCorrectionLevelTest.php
A	_doc/vendor/bacon/bacon-qr-code/test/Common/FormatInformationTest.php
A	_doc/vendor/bacon/bacon-qr-code/test/Common/ModeTest.php
A	_doc/vendor/bacon/bacon-qr-code/test/Common/ReedSolomonCodecTest.php
A	_doc/vendor/bacon/bacon-qr-code/test/Common/VersionTest.php
A	_doc/vendor/bacon/bacon-qr-code/test/Encoder/EncoderTest.php
A	_doc/vendor/bacon/bacon-qr-code/test/Encoder/MaskUtilTest.php
A	_doc/vendor/bacon/bacon-qr-code/test/Encoder/MatrixUtilTest.php
A	_doc/vendor/bacon/bacon-qr-code/test/Integration/ImagickRenderingTest.php
A	_doc/vendor/bacon/bacon-qr-code/test/Integration/__snapshots__/files/ImagickRenderingTest__testGenericQrCode__1.png
A	_doc/vendor/bacon/bacon-qr-code/test/Integration/__snapshots__/files/ImagickRenderingTest__testIssue79__1.png
A	_doc/vendor/composer/ClassLoader.php
A	_doc/vendor/composer/InstalledVersions.php
A	_doc/vendor/composer/LICENSE
A	_doc/vendor/composer/autoload_classmap.php
A	_doc/vendor/composer/autoload_namespaces.php
A	_doc/vendor/composer/autoload_psr4.php
A	_doc/vendor/composer/autoload_real.php
A	_doc/vendor/composer/autoload_static.php
A	_doc/vendor/composer/installed.json
A	_doc/vendor/composer/installed.php
A	_doc/vendor/composer/platform_check.php
A	_doc/vendor/dasprid/enum/LICENSE
A	_doc/vendor/dasprid/enum/README.md
A	_doc/vendor/dasprid/enum/composer.json
A	_doc/vendor/dasprid/enum/src/AbstractEnum.php
A	_doc/vendor/dasprid/enum/src/EnumMap.php
A	_doc/vendor/dasprid/enum/src/Exception/CloneNotSupportedException.php
A	_doc/vendor/dasprid/enum/src/Exception/ExceptionInterface.php
A	_doc/vendor/dasprid/enum/src/Exception/ExpectationException.php
A	_doc/vendor/dasprid/enum/src/Exception/IllegalArgumentException.php
A	_doc/vendor/dasprid/enum/src/Exception/MismatchException.php
A	_doc/vendor/dasprid/enum/src/Exception/SerializeNotSupportedException.php
A	_doc/vendor/dasprid/enum/src/Exception/UnserializeNotSupportedException.php
A	_doc/vendor/dasprid/enum/src/NullValue.php
A	_doc/vendor/fpdf/FAQ.htm
A	_doc/vendor/fpdf/changelog.htm
A	_doc/vendor/fpdf/doc/__construct.htm
A	_doc/vendor/fpdf/doc/acceptpagebreak.htm
A	_doc/vendor/fpdf/doc/addfont.htm
A	_doc/vendor/fpdf/doc/addlink.htm
A	_doc/vendor/fpdf/doc/addpage.htm
A	_doc/vendor/fpdf/doc/aliasnbpages.htm
A	_doc/vendor/fpdf/doc/cell.htm
A	_doc/vendor/fpdf/doc/close.htm
A	_doc/vendor/fpdf/doc/error.htm
A	_doc/vendor/fpdf/doc/footer.htm
A	_doc/vendor/fpdf/doc/getpageheight.htm
A	_doc/vendor/fpdf/doc/getpagewidth.htm
A	_doc/vendor/fpdf/doc/getstringwidth.htm
A	_doc/vendor/fpdf/doc/getx.htm
A	_doc/vendor/fpdf/doc/gety.htm
A	_doc/vendor/fpdf/doc/header.htm
A	_doc/vendor/fpdf/doc/image.htm
A	_doc/vendor/fpdf/doc/index.htm
A	_doc/vendor/fpdf/doc/line.htm
A	_doc/vendor/fpdf/doc/link.htm
A	_doc/vendor/fpdf/doc/ln.htm
A	_doc/vendor/fpdf/doc/multicell.htm
A	_doc/vendor/fpdf/doc/output.htm
A	_doc/vendor/fpdf/doc/pageno.htm
A	_doc/vendor/fpdf/doc/rect.htm
A	_doc/vendor/fpdf/doc/setauthor.htm
A	_doc/vendor/fpdf/doc/setautopagebreak.htm
A	_doc/vendor/fpdf/doc/setcompression.htm
A	_doc/vendor/fpdf/doc/setcreator.htm
A	_doc/vendor/fpdf/doc/setdisplaymode.htm
A	_doc/vendor/fpdf/doc/setdrawcolor.htm
A	_doc/vendor/fpdf/doc/setfillcolor.htm
A	_doc/vendor/fpdf/doc/setfont.htm
A	_doc/vendor/fpdf/doc/setfontsize.htm
A	_doc/vendor/fpdf/doc/setkeywords.htm
A	_doc/vendor/fpdf/doc/setleftmargin.htm
A	_doc/vendor/fpdf/doc/setlinewidth.htm
A	_doc/vendor/fpdf/doc/setlink.htm
A	_doc/vendor/fpdf/doc/setmargins.htm
A	_doc/vendor/fpdf/doc/setrightmargin.htm
A	_doc/vendor/fpdf/doc/setsubject.htm
A	_doc/vendor/fpdf/doc/settextcolor.htm
A	_doc/vendor/fpdf/doc/settitle.htm
A	_doc/vendor/fpdf/doc/settopmargin.htm
A	_doc/vendor/fpdf/doc/setx.htm
A	_doc/vendor/fpdf/doc/setxy.htm
A	_doc/vendor/fpdf/doc/sety.htm
A	_doc/vendor/fpdf/doc/text.htm
A	_doc/vendor/fpdf/doc/write.htm
A	_doc/vendor/fpdf/font/courier.php
A	_doc/vendor/fpdf/font/courierb.php
A	_doc/vendor/fpdf/font/courierbi.php
A	_doc/vendor/fpdf/font/courieri.php
A	_doc/vendor/fpdf/font/helvetica.php
A	_doc/vendor/fpdf/font/helveticab.php
A	_doc/vendor/fpdf/font/helveticabi.php
A	_doc/vendor/fpdf/font/helveticai.php
A	_doc/vendor/fpdf/font/symbol.php
A	_doc/vendor/fpdf/font/times.php
A	_doc/vendor/fpdf/font/timesb.php
A	_doc/vendor/fpdf/font/timesbi.php
A	_doc/vendor/fpdf/font/timesi.php
A	_doc/vendor/fpdf/font/zapfdingbats.php
A	_doc/vendor/fpdf/fpdf.css
A	_doc/vendor/fpdf/fpdf.php
A	_doc/vendor/fpdf/install.txt
A	_doc/vendor/fpdf/license.txt
A	_doc/vendor/fpdf/makefont/cp1250.map
A	_doc/vendor/fpdf/makefont/cp1251.map
A	_doc/vendor/fpdf/makefont/cp1252.map
A	_doc/vendor/fpdf/makefont/cp1253.map
A	_doc/vendor/fpdf/makefont/cp1254.map
A	_doc/vendor/fpdf/makefont/cp1255.map
A	_doc/vendor/fpdf/makefont/cp1257.map
A	_doc/vendor/fpdf/makefont/cp1258.map
A	_doc/vendor/fpdf/makefont/cp874.map
A	_doc/vendor/fpdf/makefont/iso-8859-1.map
A	_doc/vendor/fpdf/makefont/iso-8859-11.map
A	_doc/vendor/fpdf/makefont/iso-8859-15.map
A	_doc/vendor/fpdf/makefont/iso-8859-16.map
A	_doc/vendor/fpdf/makefont/iso-8859-2.map
A	_doc/vendor/fpdf/makefont/iso-8859-4.map
A	_doc/vendor/fpdf/makefont/iso-8859-5.map
A	_doc/vendor/fpdf/makefont/iso-8859-7.map
A	_doc/vendor/fpdf/makefont/iso-8859-9.map
A	_doc/vendor/fpdf/makefont/koi8-r.map
A	_doc/vendor/fpdf/makefont/koi8-u.map
A	_doc/vendor/fpdf/makefont/makefont.php
A	_doc/vendor/fpdf/makefont/ttfparser.php
A	_doc/vendor/fpdf/tutorial/20k_c1.txt
A	_doc/vendor/fpdf/tutorial/20k_c2.txt
A	_doc/vendor/fpdf/tutorial/CevicheOne-Regular-Licence.txt
A	_doc/vendor/fpdf/tutorial/CevicheOne-Regular.php
A	_doc/vendor/fpdf/tutorial/CevicheOne-Regular.ttf
A	_doc/vendor/fpdf/tutorial/CevicheOne-Regular.z
A	_doc/vendor/fpdf/tutorial/countries.txt
A	_doc/vendor/fpdf/tutorial/index.htm
A	_doc/vendor/fpdf/tutorial/logo.png
A	_doc/vendor/fpdf/tutorial/makefont.php
A	_doc/vendor/fpdf/tutorial/tuto1.htm
A	_doc/vendor/fpdf/tutorial/tuto1.php
A	_doc/vendor/fpdf/tutorial/tuto2.htm
A	_doc/vendor/fpdf/tutorial/tuto2.php
A	_doc/vendor/fpdf/tutorial/tuto3.htm
A	_doc/vendor/fpdf/tutorial/tuto3.php
A	_doc/vendor/fpdf/tutorial/tuto4.htm
A	_doc/vendor/fpdf/tutorial/tuto4.php
A	_doc/vendor/fpdf/tutorial/tuto5.htm
A	_doc/vendor/fpdf/tutorial/tuto5.php
A	_doc/vendor/fpdf/tutorial/tuto6.htm
A	_doc/vendor/fpdf/tutorial/tuto6.php
A	_doc/vendor/fpdf/tutorial/tuto7.htm
A	_doc/vendor/fpdf/tutorial/tuto7.php
A	_doc/vendor/mpdf/mpdf/.gitignore
A	_doc/vendor/mpdf/mpdf/.travis.yml
A	_doc/vendor/mpdf/mpdf/CHANGELOG.txt
A	_doc/vendor/mpdf/mpdf/CREDITS.txt
A	_doc/vendor/mpdf/mpdf/LICENSE.txt
A	_doc/vendor/mpdf/mpdf/MpdfException.php
A	_doc/vendor/mpdf/mpdf/README.md
A	_doc/vendor/mpdf/mpdf/Tag.php
A	_doc/vendor/mpdf/mpdf/classes/barcode.php
A	_doc/vendor/mpdf/mpdf/classes/bmp.php
A	_doc/vendor/mpdf/mpdf/classes/cssmgr.php
A	_doc/vendor/mpdf/mpdf/classes/directw.php
A	_doc/vendor/mpdf/mpdf/classes/gif.php
A	_doc/vendor/mpdf/mpdf/classes/grad.php
A	_doc/vendor/mpdf/mpdf/classes/indic.php
A	_doc/vendor/mpdf/mpdf/classes/meter.php
A	_doc/vendor/mpdf/mpdf/classes/mpdfform.php
A	_doc/vendor/mpdf/mpdf/classes/myanmar.php
A	_doc/vendor/mpdf/mpdf/classes/otl.php
A	_doc/vendor/mpdf/mpdf/classes/otl_dump.php
A	_doc/vendor/mpdf/mpdf/classes/sea.php
A	_doc/vendor/mpdf/mpdf/classes/svg.php
A	_doc/vendor/mpdf/mpdf/classes/tocontents.php
A	_doc/vendor/mpdf/mpdf/classes/ttfontsuni.php
A	_doc/vendor/mpdf/mpdf/classes/ttfontsuni_analysis.php
A	_doc/vendor/mpdf/mpdf/classes/ucdn.php
A	_doc/vendor/mpdf/mpdf/classes/wmf.php
A	_doc/vendor/mpdf/mpdf/collations/Afrikaans_South_Africa.php
A	_doc/vendor/mpdf/mpdf/collations/Albanian_Albania.php
A	_doc/vendor/mpdf/mpdf/collations/Alsatian_France.php
A	_doc/vendor/mpdf/mpdf/collations/Arabic_Algeria.php
A	_doc/vendor/mpdf/mpdf/collations/Arabic_Bahrain.php
A	_doc/vendor/mpdf/mpdf/collations/Arabic_Egypt.php
A	_doc/vendor/mpdf/mpdf/collations/Arabic_Iraq.php
A	_doc/vendor/mpdf/mpdf/collations/Arabic_Jordan.php
A	_doc/vendor/mpdf/mpdf/collations/Arabic_Kuwait.php
A	_doc/vendor/mpdf/mpdf/collations/Arabic_Lebanon.php
A	_doc/vendor/mpdf/mpdf/collations/Arabic_Libya.php
A	_doc/vendor/mpdf/mpdf/collations/Arabic_Morocco.php
A	_doc/vendor/mpdf/mpdf/collations/Arabic_Oman.php
A	_doc/vendor/mpdf/mpdf/collations/Arabic_Pseudo_RTL.php
A	_doc/vendor/mpdf/mpdf/collations/Arabic_Qatar.php
A	_doc/vendor/mpdf/mpdf/collations/Arabic_Saudi_Arabia.php
A	_doc/vendor/mpdf/mpdf/collations/Arabic_Syria.php
A	_doc/vendor/mpdf/mpdf/collations/Arabic_Tunisia.php
A	_doc/vendor/mpdf/mpdf/collations/Arabic_Yemen.php
A	_doc/vendor/mpdf/mpdf/collations/Azeri_(Cyrillic)_Azerbaijan.php
A	_doc/vendor/mpdf/mpdf/collations/Azeri_(Latin)_Azerbaijan.php
A	_doc/vendor/mpdf/mpdf/collations/Bashkir_Russia.php
A	_doc/vendor/mpdf/mpdf/collations/Basque_Spain.php
A	_doc/vendor/mpdf/mpdf/collations/Belarusian_Belarus.php
A	_doc/vendor/mpdf/mpdf/collations/Bosnian_(Cyrillic)_Bosnia_and_Herzegovina.php
A	_doc/vendor/mpdf/mpdf/collations/Bosnian_(Latin)_Bosnia_and_Herzegovina.php
A	_doc/vendor/mpdf/mpdf/collations/Breton_France.php
A	_doc/vendor/mpdf/mpdf/collations/Bulgarian_Bulgaria.php
A	_doc/vendor/mpdf/mpdf/collations/Catalan_Spain.php
A	_doc/vendor/mpdf/mpdf/collations/Corsican_France.php
A	_doc/vendor/mpdf/mpdf/collations/Croatian_(Latin)_Bosnia_and_Herzegovina.php
A	_doc/vendor/mpdf/mpdf/collations/Croatian_Croatia.php
A	_doc/vendor/mpdf/mpdf/collations/Czech_Czech_Republic.php
A	_doc/vendor/mpdf/mpdf/collations/Danish_Denmark.php
A	_doc/vendor/mpdf/mpdf/collations/Dari_Afghanistan.php
A	_doc/vendor/mpdf/mpdf/collations/Dutch_Belgium.php
A	_doc/vendor/mpdf/mpdf/collations/Dutch_Netherlands.php
A	_doc/vendor/mpdf/mpdf/collations/English_Australia.php
A	_doc/vendor/mpdf/mpdf/collations/English_Belize.php
A	_doc/vendor/mpdf/mpdf/collations/English_Canada.php
A	_doc/vendor/mpdf/mpdf/collations/English_Caribbean.php
A	_doc/vendor/mpdf/mpdf/collations/English_India.php
A	_doc/vendor/mpdf/mpdf/collations/English_Ireland.php
A	_doc/vendor/mpdf/mpdf/collations/English_Jamaica.php
A	_doc/vendor/mpdf/mpdf/collations/English_Malaysia.php
A	_doc/vendor/mpdf/mpdf/collations/English_New_Zealand.php
A	_doc/vendor/mpdf/mpdf/collations/English_Republic_of_the_Philippines.php
A	_doc/vendor/mpdf/mpdf/collations/English_Singapore.php
A	_doc/vendor/mpdf/mpdf/collations/English_South_Africa.php
A	_doc/vendor/mpdf/mpdf/collations/English_Trinidad_and_Tobago.php
A	_doc/vendor/mpdf/mpdf/collations/English_United_Kingdom.php
A	_doc/vendor/mpdf/mpdf/collations/English_United_States.php
A	_doc/vendor/mpdf/mpdf/collations/English_Zimbabwe.php
A	_doc/vendor/mpdf/mpdf/collations/Estonian_Estonia.php
A	_doc/vendor/mpdf/mpdf/collations/Faroese_Faroe_Islands.php
A	_doc/vendor/mpdf/mpdf/collations/Filipino_Philippines.php
A	_doc/vendor/mpdf/mpdf/collations/Finnish_Finland.php
A	_doc/vendor/mpdf/mpdf/collations/French_Belgium.php
A	_doc/vendor/mpdf/mpdf/collations/French_Canada.php
A	_doc/vendor/mpdf/mpdf/collations/French_France.php
A	_doc/vendor/mpdf/mpdf/collations/French_Luxembourg.php
A	_doc/vendor/mpdf/mpdf/collations/French_Principality_of_Monaco.php
A	_doc/vendor/mpdf/mpdf/collations/French_Switzerland.php
A	_doc/vendor/mpdf/mpdf/collations/Frisian_Netherlands.php
A	_doc/vendor/mpdf/mpdf/collations/Galician_Spain.php
A	_doc/vendor/mpdf/mpdf/collations/German_Austria.php
A	_doc/vendor/mpdf/mpdf/collations/German_Germany.php
A	_doc/vendor/mpdf/mpdf/collations/German_Liechtenstein.php
A	_doc/vendor/mpdf/mpdf/collations/German_Luxembourg.php
A	_doc/vendor/mpdf/mpdf/collations/German_Switzerland.php
A	_doc/vendor/mpdf/mpdf/collations/Greek_Greece.php
A	_doc/vendor/mpdf/mpdf/collations/Greenlandic_Greenland.php
A	_doc/vendor/mpdf/mpdf/collations/Hausa_(Latin)_Nigeria.php
A	_doc/vendor/mpdf/mpdf/collations/Hebrew_Israel.php
A	_doc/vendor/mpdf/mpdf/collations/Hungarian_Hungary.php
A	_doc/vendor/mpdf/mpdf/collations/Icelandic_Iceland.php
A	_doc/vendor/mpdf/mpdf/collations/Igbo_Nigeria.php
A	_doc/vendor/mpdf/mpdf/collations/Indonesian_Indonesia.php
A	_doc/vendor/mpdf/mpdf/collations/Inuktitut_(Latin)_Canada.php
A	_doc/vendor/mpdf/mpdf/collations/Invariant_Language_Invariant_Country.php
A	_doc/vendor/mpdf/mpdf/collations/Irish_Ireland.php
A	_doc/vendor/mpdf/mpdf/collations/Italian_Italy.php
A	_doc/vendor/mpdf/mpdf/collations/Italian_Switzerland.php
A	_doc/vendor/mpdf/mpdf/collations/Kinyarwanda_Rwanda.php
A	_doc/vendor/mpdf/mpdf/collations/Kiswahili_Kenya.php
A	_doc/vendor/mpdf/mpdf/collations/Kyrgyz_Kyrgyzstan.php
A	_doc/vendor/mpdf/mpdf/collations/Latvian_Latvia.php
A	_doc/vendor/mpdf/mpdf/collations/Lithuanian_Lithuania.php
A	_doc/vendor/mpdf/mpdf/collations/Lower_Sorbian_Germany.php
A	_doc/vendor/mpdf/mpdf/collations/Luxembourgish_Luxembourg.php
A	_doc/vendor/mpdf/mpdf/collations/Macedonian_(FYROM)_Macedonia_(FYROM).php
A	_doc/vendor/mpdf/mpdf/collations/Malay_Brunei_Darussalam.php
A	_doc/vendor/mpdf/mpdf/collations/Malay_Malaysia.php
A	_doc/vendor/mpdf/mpdf/collations/Mapudungun_Chile.php
A	_doc/vendor/mpdf/mpdf/collations/Mohawk_Canada.php
A	_doc/vendor/mpdf/mpdf/collations/Mongolian_(Cyrillic)_Mongolia.php
A	_doc/vendor/mpdf/mpdf/collations/Norwegian_(Nynorsk)_Norway.php
A	_doc/vendor/mpdf/mpdf/collations/Occitan_France.php
A	_doc/vendor/mpdf/mpdf/collations/Persian_Iran.php
A	_doc/vendor/mpdf/mpdf/collations/Polish_Poland.php
A	_doc/vendor/mpdf/mpdf/collations/Portuguese_Brazil.php
A	_doc/vendor/mpdf/mpdf/collations/Portuguese_Portugal.php
A	_doc/vendor/mpdf/mpdf/collations/Quechua_Bolivia.php
A	_doc/vendor/mpdf/mpdf/collations/Quechua_Ecuador.php
A	_doc/vendor/mpdf/mpdf/collations/Quechua_Peru.php
A	_doc/vendor/mpdf/mpdf/collations/Romanian_Romania.php
A	_doc/vendor/mpdf/mpdf/collations/Romansh_Switzerland.php
A	_doc/vendor/mpdf/mpdf/collations/Russian_Russia.php
A	_doc/vendor/mpdf/mpdf/collations/Sami_(Inari)_Finland.php
A	_doc/vendor/mpdf/mpdf/collations/Sami_(Lule)_Norway.php
A	_doc/vendor/mpdf/mpdf/collations/Sami_(Lule)_Sweden.php
A	_doc/vendor/mpdf/mpdf/collations/Sami_(Northern)_Finland.php
A	_doc/vendor/mpdf/mpdf/collations/Sami_(Northern)_Norway.php
A	_doc/vendor/mpdf/mpdf/collations/Sami_(Northern)_Sweden.php
A	_doc/vendor/mpdf/mpdf/collations/Sami_(Skolt)_Finland.php
A	_doc/vendor/mpdf/mpdf/collations/Sami_(Southern)_Norway.php
A	_doc/vendor/mpdf/mpdf/collations/Sami_(Southern)_Sweden.php
A	_doc/vendor/mpdf/mpdf/collations/Serbian_(Cyrillic)_Bosnia_and_Herzegovina.php
A	_doc/vendor/mpdf/mpdf/collations/Serbian_(Cyrillic)_Serbia.php
A	_doc/vendor/mpdf/mpdf/collations/Serbian_(Latin)_Bosnia_and_Herzegovina.php
A	_doc/vendor/mpdf/mpdf/collations/Serbian_(Latin)_Serbia.php
A	_doc/vendor/mpdf/mpdf/collations/Sesotho_sa_Leboa_South_Africa.php
A	_doc/vendor/mpdf/mpdf/collations/Setswana_South_Africa.php
A	_doc/vendor/mpdf/mpdf/collations/Slovak_Slovakia.php
A	_doc/vendor/mpdf/mpdf/collations/Slovenian_Slovenia.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_Argentina.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_Bolivia.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_Chile.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_Colombia.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_Costa_Rica.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_Dominican_Republic.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_Ecuador.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_El_Salvador.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_Guatemala.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_Honduras.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_Mexico.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_Nicaragua.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_Panama.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_Paraguay.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_Peru.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_Puerto_Rico.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_Spain.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_United_States.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_Uruguay.php
A	_doc/vendor/mpdf/mpdf/collations/Spanish_Venezuela.php
A	_doc/vendor/mpdf/mpdf/collations/Swedish_Finland.php
A	_doc/vendor/mpdf/mpdf/collations/Swedish_Sweden.php
A	_doc/vendor/mpdf/mpdf/collations/Tajik_(Cyrillic)_Tajikistan.php
A	_doc/vendor/mpdf/mpdf/collations/Tamazight_(Latin)_Algeria.php
A	_doc/vendor/mpdf/mpdf/collations/Tatar_Russia.php
A	_doc/vendor/mpdf/mpdf/collations/Turkish_Turkey.php
A	_doc/vendor/mpdf/mpdf/collations/Turkmen_Turkmenistan.php
A	_doc/vendor/mpdf/mpdf/collations/Ukrainian_Ukraine.php
A	_doc/vendor/mpdf/mpdf/collations/Upper_Sorbian_Germany.php
A	_doc/vendor/mpdf/mpdf/collations/Urdu_Islamic_Republic_of_Pakistan.php
A	_doc/vendor/mpdf/mpdf/collations/Uzbek_(Cyrillic)_Uzbekistan.php
A	_doc/vendor/mpdf/mpdf/collations/Uzbek_(Latin)_Uzbekistan.php
A	_doc/vendor/mpdf/mpdf/collations/Vietnamese_Vietnam.php
A	_doc/vendor/mpdf/mpdf/collations/Welsh_United_Kingdom.php
A	_doc/vendor/mpdf/mpdf/collations/Wolof_Senegal.php
A	_doc/vendor/mpdf/mpdf/collations/Yakut_Russia.php
A	_doc/vendor/mpdf/mpdf/collations/Yoruba_Nigeria.php
A	_doc/vendor/mpdf/mpdf/collations/isiXhosa_South_Africa.php
A	_doc/vendor/mpdf/mpdf/collations/isiZulu_South_Africa.php
A	_doc/vendor/mpdf/mpdf/composer.json
A	_doc/vendor/mpdf/mpdf/compress.php
A	_doc/vendor/mpdf/mpdf/config.php
A	_doc/vendor/mpdf/mpdf/config_fonts-distr-without-OTL.php
A	_doc/vendor/mpdf/mpdf/config_fonts.php
A	_doc/vendor/mpdf/mpdf/config_lang2fonts.php
A	_doc/vendor/mpdf/mpdf/config_script2lang.php
A	_doc/vendor/mpdf/mpdf/font/ccourier.php
A	_doc/vendor/mpdf/mpdf/font/ccourierb.php
A	_doc/vendor/mpdf/mpdf/font/ccourierbi.php
A	_doc/vendor/mpdf/mpdf/font/ccourieri.php
A	_doc/vendor/mpdf/mpdf/font/chelvetica.php
A	_doc/vendor/mpdf/mpdf/font/chelveticab.php
A	_doc/vendor/mpdf/mpdf/font/chelveticabi.php
A	_doc/vendor/mpdf/mpdf/font/chelveticai.php
A	_doc/vendor/mpdf/mpdf/font/csymbol.php
A	_doc/vendor/mpdf/mpdf/font/ctimes.php
A	_doc/vendor/mpdf/mpdf/font/ctimesb.php
A	_doc/vendor/mpdf/mpdf/font/ctimesbi.php
A	_doc/vendor/mpdf/mpdf/font/ctimesi.php
A	_doc/vendor/mpdf/mpdf/font/czapfdingbats.php
A	_doc/vendor/mpdf/mpdf/graph.php
A	_doc/vendor/mpdf/mpdf/graph_cache/.gitignore
A	_doc/vendor/mpdf/mpdf/iccprofiles/SWOP2006_Coated5v2.icc
A	_doc/vendor/mpdf/mpdf/iccprofiles/sRGB_IEC61966-2-1.icc
A	_doc/vendor/mpdf/mpdf/includes/CJKdata.php
A	_doc/vendor/mpdf/mpdf/includes/functions.php
A	_doc/vendor/mpdf/mpdf/includes/linebrdictK.dat
A	_doc/vendor/mpdf/mpdf/includes/linebrdictL.dat
A	_doc/vendor/mpdf/mpdf/includes/linebrdictT.dat
A	_doc/vendor/mpdf/mpdf/includes/no_image.jpg
A	_doc/vendor/mpdf/mpdf/includes/out.php
A	_doc/vendor/mpdf/mpdf/includes/subs_core.php
A	_doc/vendor/mpdf/mpdf/includes/subs_win-1252.php
A	_doc/vendor/mpdf/mpdf/includes/upperCase.php
A	_doc/vendor/mpdf/mpdf/lang2fonts.css
A	_doc/vendor/mpdf/mpdf/mpdf.css
A	_doc/vendor/mpdf/mpdf/mpdf.php
A	_doc/vendor/mpdf/mpdf/patterns/NOTES.txt
A	_doc/vendor/mpdf/mpdf/patterns/de.php
A	_doc/vendor/mpdf/mpdf/patterns/dictionary.txt
A	_doc/vendor/mpdf/mpdf/patterns/en.php
A	_doc/vendor/mpdf/mpdf/patterns/es.php
A	_doc/vendor/mpdf/mpdf/patterns/fi.php
A	_doc/vendor/mpdf/mpdf/patterns/fr.php
A	_doc/vendor/mpdf/mpdf/patterns/it.php
A	_doc/vendor/mpdf/mpdf/patterns/nl.php
A	_doc/vendor/mpdf/mpdf/patterns/pl.php
A	_doc/vendor/mpdf/mpdf/patterns/ru.php
A	_doc/vendor/mpdf/mpdf/patterns/sv.php
A	_doc/vendor/mpdf/mpdf/phpunit.xml
A	_doc/vendor/mpdf/mpdf/progbar.css
A	_doc/vendor/mpdf/mpdf/qrcode/_LGPL.txt
A	_doc/vendor/mpdf/mpdf/qrcode/_lisez_moi.txt
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele10.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele11.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele12.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele13.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele14.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele15.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele16.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele17.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele18.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele19.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele20.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele21.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele22.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele23.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele24.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele25.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele26.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele27.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele28.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele29.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele30.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele31.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele32.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele33.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele34.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele35.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele36.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele37.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele38.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele39.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele4.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele40.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele5.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele6.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele7.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele8.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/modele9.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv10_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv10_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv10_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv10_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv11_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv11_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv11_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv11_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv12_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv12_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv12_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv12_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv13_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv13_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv13_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv13_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv14_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv14_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv14_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv14_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv15_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv15_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv15_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv15_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv16_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv16_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv16_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv16_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv17_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv17_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv17_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv17_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv18_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv18_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv18_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv18_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv19_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv19_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv19_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv19_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv1_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv1_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv1_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv1_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv20_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv20_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv20_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv20_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv21_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv21_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv21_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv21_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv22_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv22_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv22_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv22_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv23_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv23_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv23_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv23_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv24_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv24_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv24_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv24_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv25_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv25_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv25_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv25_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv26_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv26_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv26_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv26_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv27_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv27_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv27_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv27_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv28_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv28_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv28_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv28_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv29_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv29_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv29_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv29_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv2_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv2_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv2_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv2_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv30_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv30_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv30_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv30_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv31_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv31_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv31_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv31_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv32_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv32_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv32_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv32_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv33_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv33_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv33_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv33_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv34_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv34_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv34_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv34_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv35_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv35_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv35_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv35_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv36_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv36_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv36_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv36_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv37_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv37_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv37_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv37_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv38_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv38_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv38_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv38_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv39_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv39_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv39_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv39_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv3_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv3_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv3_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv3_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv40_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv40_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv40_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv40_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv4_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv4_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv4_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv4_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv5_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv5_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv5_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv5_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv6_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv6_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv6_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv6_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv7_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv7_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv7_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv7_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv8_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv8_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv8_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv8_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv9_0.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv9_1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv9_2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrv9_3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr1.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr10.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr11.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr12.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr13.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr14.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr15.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr16.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr17.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr18.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr19.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr2.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr20.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr21.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr22.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr23.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr24.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr25.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr26.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr27.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr28.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr29.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr3.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr30.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr31.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr32.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr33.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr34.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr35.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr36.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr37.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr38.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr39.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr4.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr40.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr5.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr6.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr7.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr8.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/qrvfr9.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc10.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc13.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc15.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc16.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc17.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc18.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc20.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc22.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc24.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc26.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc28.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc30.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc32.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc34.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc36.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc40.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc42.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc44.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc46.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc48.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc50.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc52.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc54.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc56.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc58.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc60.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc62.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc64.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc66.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc68.dat
A	_doc/vendor/mpdf/mpdf/qrcode/data/rsc7.dat
A	_doc/vendor/mpdf/mpdf/qrcode/image.php
A	_doc/vendor/mpdf/mpdf/qrcode/index.php
A	_doc/vendor/mpdf/mpdf/qrcode/qrcode.class.php
A	_doc/vendor/mpdf/mpdf/tmp/.gitignore
A	_doc/vendor/mpdf/mpdf/ttfontdata/.gitignore
A	_doc/vendor/mpdf/mpdf/ttfonts/AboriginalSansREGULAR.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/Abyssinica_SIL.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/Aegean.otf
A	_doc/vendor/mpdf/mpdf/ttfonts/Aegyptus.otf
A	_doc/vendor/mpdf/mpdf/ttfonts/Akkadian.otf
A	_doc/vendor/mpdf/mpdf/ttfonts/DBSILBR.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSans-Bold.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSans-BoldOblique.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSans-Oblique.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSans.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSansCondensed-Bold.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSansCondensed-BoldOblique.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSansCondensed-Oblique.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSansCondensed.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSansMono-Bold.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSansMono-BoldOblique.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSansMono-Oblique.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSansMono.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSerif-Bold.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSerif-BoldItalic.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSerif-Italic.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSerif.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSerifCondensed-Bold.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSerifCondensed-BoldItalic.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSerifCondensed-Italic.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuSerifCondensed.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DejaVuinfo.txt
A	_doc/vendor/mpdf/mpdf/ttfonts/Dhyana-Bold.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/Dhyana-Regular.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/DhyanaOFL.txt
A	_doc/vendor/mpdf/mpdf/ttfonts/FreeMono.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/FreeMonoBold.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/FreeMonoBoldOblique.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/FreeMonoOblique.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/FreeSans.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/FreeSansBold.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/FreeSansBoldOblique.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/FreeSansOblique.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/FreeSerif.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/FreeSerifBold.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/FreeSerifBoldItalic.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/FreeSerifItalic.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/GNUFreeFontinfo.txt
A	_doc/vendor/mpdf/mpdf/ttfonts/Garuda-Bold.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/Garuda-BoldOblique.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/Garuda-Oblique.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/Garuda.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/Jomolhari-OFL.txt
A	_doc/vendor/mpdf/mpdf/ttfonts/Jomolhari.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/KhmerOFL.txt
A	_doc/vendor/mpdf/mpdf/ttfonts/KhmerOS.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/Lateef font OFL.txt
A	_doc/vendor/mpdf/mpdf/ttfonts/LateefRegOT.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/Lohit-Kannada.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/LohitKannadaOFL.txt
A	_doc/vendor/mpdf/mpdf/ttfonts/Padauk-book.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/Pothana2000.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/Quivira.otf
A	_doc/vendor/mpdf/mpdf/ttfonts/Sun-ExtA.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/Sun-ExtB.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/SundaneseUnicode-1.0.5.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/SyrCOMEdessa.otf
A	_doc/vendor/mpdf/mpdf/ttfonts/SyrCOMEdessa_license.txt
A	_doc/vendor/mpdf/mpdf/ttfonts/TaameyDavidCLM-LICENSE.txt
A	_doc/vendor/mpdf/mpdf/ttfonts/TaameyDavidCLM-Medium.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/TaiHeritagePro.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/Tharlon-Regular.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/TharlonOFL.txt
A	_doc/vendor/mpdf/mpdf/ttfonts/UnBatang_0613.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/Uthman.otf
A	_doc/vendor/mpdf/mpdf/ttfonts/XB Riyaz.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/XB RiyazBd.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/XB RiyazBdIt.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/XB RiyazIt.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/XW Zar Font Info.txt
A	_doc/vendor/mpdf/mpdf/ttfonts/ZawgyiOne.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/ayar.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/damase_v.2.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/kaputaunicode.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/lannaalif-v1-03.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/ocrb10.ttf
A	_doc/vendor/mpdf/mpdf/ttfonts/ocrbinfo.txt
A	_doc/vendor/paragonie/constant_time_encoding/LICENSE.txt
A	_doc/vendor/paragonie/constant_time_encoding/README.md
A	_doc/vendor/paragonie/constant_time_encoding/composer.json
A	_doc/vendor/paragonie/constant_time_encoding/src/Base32.php
A	_doc/vendor/paragonie/constant_time_encoding/src/Base32Hex.php
A	_doc/vendor/paragonie/constant_time_encoding/src/Base64.php
A	_doc/vendor/paragonie/constant_time_encoding/src/Base64DotSlash.php
A	_doc/vendor/paragonie/constant_time_encoding/src/Base64DotSlashOrdered.php
A	_doc/vendor/paragonie/constant_time_encoding/src/Base64UrlSafe.php
A	_doc/vendor/paragonie/constant_time_encoding/src/Binary.php
A	_doc/vendor/paragonie/constant_time_encoding/src/EncoderInterface.php
A	_doc/vendor/paragonie/constant_time_encoding/src/Encoding.php
A	_doc/vendor/paragonie/constant_time_encoding/src/Hex.php
A	_doc/vendor/paragonie/constant_time_encoding/src/RFC4648.php
A	_doc/vendor/phpmailer/phpmailer/COMMITMENT
A	_doc/vendor/phpmailer/phpmailer/LICENSE
A	_doc/vendor/phpmailer/phpmailer/README.md
A	_doc/vendor/phpmailer/phpmailer/SECURITY.md
A	_doc/vendor/phpmailer/phpmailer/SMTPUTF8.md
A	_doc/vendor/phpmailer/phpmailer/VERSION
A	_doc/vendor/phpmailer/phpmailer/composer.json
A	_doc/vendor/phpmailer/phpmailer/get_oauth_token.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-af.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-ar.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-as.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-az.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-ba.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-be.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-bg.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-bn.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-ca.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-cs.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-da.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-de.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-el.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-eo.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-es.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-et.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-fa.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-fi.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-fo.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-fr.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-gl.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-he.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-hi.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-hr.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-hu.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-hy.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-id.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-it.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-ja.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-ka.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-ko.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-ku.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-lt.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-lv.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-mg.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-mn.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-ms.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-nb.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-nl.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-pl.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-pt.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-pt_br.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-ro.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-ru.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-si.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-sk.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-sl.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-sr.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-sr_latn.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-sv.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-tl.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-tr.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-uk.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-ur.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-vi.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-zh.php
A	_doc/vendor/phpmailer/phpmailer/language/phpmailer.lang-zh_cn.php
A	_doc/vendor/phpmailer/phpmailer/src/DSNConfigurator.php
A	_doc/vendor/phpmailer/phpmailer/src/Exception.php
A	_doc/vendor/phpmailer/phpmailer/src/OAuth.php
A	_doc/vendor/phpmailer/phpmailer/src/OAuthTokenProvider.php
A	_doc/vendor/phpmailer/phpmailer/src/PHPMailer.php
A	_doc/vendor/phpmailer/phpmailer/src/POP3.php
A	_doc/vendor/phpmailer/phpmailer/src/SMTP.php
A	_doc/vendor/pragmarx/google2fa/.github/workflows/run-tests.yml
A	_doc/vendor/pragmarx/google2fa/CHANGELOG.md
A	_doc/vendor/pragmarx/google2fa/LICENSE.md
A	_doc/vendor/pragmarx/google2fa/README.md
A	_doc/vendor/pragmarx/google2fa/composer.json
A	_doc/vendor/pragmarx/google2fa/src/Exceptions/Contracts/Google2FA.php
A	_doc/vendor/pragmarx/google2fa/src/Exceptions/Contracts/IncompatibleWithGoogleAuthenticator.php
A	_doc/vendor/pragmarx/google2fa/src/Exceptions/Contracts/InvalidAlgorithm.php
A	_doc/vendor/pragmarx/google2fa/src/Exceptions/Contracts/InvalidCharacters.php
A	_doc/vendor/pragmarx/google2fa/src/Exceptions/Contracts/SecretKeyTooShort.php
A	_doc/vendor/pragmarx/google2fa/src/Exceptions/Google2FAException.php
A	_doc/vendor/pragmarx/google2fa/src/Exceptions/IncompatibleWithGoogleAuthenticatorException.php
A	_doc/vendor/pragmarx/google2fa/src/Exceptions/InvalidAlgorithmException.php
A	_doc/vendor/pragmarx/google2fa/src/Exceptions/InvalidCharactersException.php
A	_doc/vendor/pragmarx/google2fa/src/Exceptions/SecretKeyTooShortException.php
A	_doc/vendor/pragmarx/google2fa/src/Google2FA.php
A	_doc/vendor/pragmarx/google2fa/src/Support/Base32.php
A	_doc/vendor/pragmarx/google2fa/src/Support/Constants.php
A	_doc/vendor/pragmarx/google2fa/src/Support/QRCode.php
A	_doc/vendor/setasign/fpdi/LICENSE
A	_doc/vendor/setasign/fpdi/README.md
A	_doc/vendor/setasign/fpdi/composer.json
A	_doc/vendor/setasign/fpdi/filters/FilterASCII85.php
A	_doc/vendor/setasign/fpdi/filters/FilterASCIIHexDecode.php
A	_doc/vendor/setasign/fpdi/filters/FilterLZW.php
A	_doc/vendor/setasign/fpdi/fpdf_tpl.php
A	_doc/vendor/setasign/fpdi/fpdi.php
A	_doc/vendor/setasign/fpdi/fpdi_bridge.php
A	_doc/vendor/setasign/fpdi/fpdi_pdf_parser.php
A	_doc/vendor/setasign/fpdi/pdf_context.php
A	_doc/vendor/setasign/fpdi/pdf_parser.php

commit eb0586a6be75656f653757af578da3e5bfb76c9b	refs/remotes/origin/bruno_branch
Author: Bruno Costa <1231247@isep.ipp.pt>
Date:   Sat Jul 5 19:26:34 2025 +0100

    relatorios

M	BLL/RH/BLL_relatorios.php
M	DAL/RH/DAL_relatorios.php
M	UI/RH/relatorios.php
A	UI/RH/relatorios_ajax.php
A	UI/RH/relatorios_pdf.php
M	assets/CSS/RH/relatorios.css

commit d94af4b55b6c51ce095740666f224f665a2ae09c	refs/remotes/origin/bruno_branch
Author: Bruno Costa <1231247@isep.ipp.pt>
Date:   Sat Jul 5 16:36:14 2025 +0100

    4

M	BLL/Colaborador/BLL_ficha_colaborador.php
M	BLL/Comuns/BLL_forgot_password.php
M	BLL/Comuns/BLL_login.php
M	BLL/Comuns/BLL_notificacoes.php
M	BLL/Coordenador/BLL_dashboard_coordenador.php
M	BLL/RH/BLL_colaboradores_gerir.php
M	BLL/RH/BLL_dashboard_rh.php
A	BLL/RH/DAL_colaboradores_gerir.php
A	BLL/RH/ajax_onboarding_dados.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
M	DAL/Comuns/DAL_login.php
M	DAL/Comuns/DAL_notificacoes.php
M	DAL/Comuns/DAL_perfil.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
M	DAL/RH/DAL_colaboradores_gerir.php
M	DAL/RH/DAL_dashboard_rh.php
M	UI/Colaborador/beneficios.php
M	UI/Colaborador/dashboard_colaborador.php
M	UI/Colaborador/ferias.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Colaborador/formacoes.php
M	UI/Colaborador/pagina_inicial_colaborador.php
M	UI/Colaborador/pedir_ferias.php
M	UI/Colaborador/recibos.php
M	UI/Comuns/login.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Convidado/onboarding_convidado.php
M	UI/Coordenador/dashboard_coordenador.php
A	UI/Coordenador/dashboard_coordenador_ajax.php
M	UI/Coordenador/equipa.php
M	UI/Coordenador/pagina_inicial_coordenador.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
A	UI/RH/dashboard_rh_ajax.php
M	UI/RH/equipa.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
M	UI/RH/gerir_beneficios.php
M	UI/RH/gerir_formacoes.php
A	UI/RH/recibos_submeter.php
M	UI/RH/relatorios.php
A	Uploads/Recibos/1231247_03_2025.pdf
A	Uploads/Recibos/1231247_06_2021.pdf
M	assets/CSS/Colaborador/ficha_colaborador.css
M	assets/CSS/Colaborador/pagina_inicial_colaborador.css
M	assets/CSS/Comuns/notificacoes.css
M	assets/CSS/Comuns/perfil.css
A	assets/CSS/Convidado/onboarding_convidado.css
M	assets/CSS/Coordenador/dashboard_coordenador.css
M	assets/CSS/RH/colaborador_novo.css
M	assets/CSS/RH/colaboradores_gerir.css
M	assets/CSS/RH/dashboard_rh.css
M	assets/CSS/RH/equipa_editar.css
M	assets/CSS/RH/equipas.css
M	assets/CSS/RH/gerir_recibos.css
M	assets/CSS/RH/pagina_inicial.css
A	assets/CSS/RH/recibos_submeter.css
M	composer.json
M	composer.lock
M	vendor/autoload.php
A	vendor/bacon/bacon-qr-code/LICENSE
A	vendor/bacon/bacon-qr-code/README.md
A	vendor/bacon/bacon-qr-code/composer.json
A	vendor/bacon/bacon-qr-code/phpunit.xml.dist
A	vendor/bacon/bacon-qr-code/src/Common/BitArray.php
A	vendor/bacon/bacon-qr-code/src/Common/BitMatrix.php
A	vendor/bacon/bacon-qr-code/src/Common/BitUtils.php
A	vendor/bacon/bacon-qr-code/src/Common/CharacterSetEci.php
A	vendor/bacon/bacon-qr-code/src/Common/EcBlock.php
A	vendor/bacon/bacon-qr-code/src/Common/EcBlocks.php
A	vendor/bacon/bacon-qr-code/src/Common/ErrorCorrectionLevel.php
A	vendor/bacon/bacon-qr-code/src/Common/FormatInformation.php
A	vendor/bacon/bacon-qr-code/src/Common/Mode.php
A	vendor/bacon/bacon-qr-code/src/Common/ReedSolomonCodec.php
A	vendor/bacon/bacon-qr-code/src/Common/Version.php
A	vendor/bacon/bacon-qr-code/src/Encoder/BlockPair.php
A	vendor/bacon/bacon-qr-code/src/Encoder/ByteMatrix.php
A	vendor/bacon/bacon-qr-code/src/Encoder/Encoder.php
A	vendor/bacon/bacon-qr-code/src/Encoder/MaskUtil.php
A	vendor/bacon/bacon-qr-code/src/Encoder/MatrixUtil.php
A	vendor/bacon/bacon-qr-code/src/Encoder/QrCode.php
A	vendor/bacon/bacon-qr-code/src/Exception/ExceptionInterface.php
A	vendor/bacon/bacon-qr-code/src/Exception/InvalidArgumentException.php
A	vendor/bacon/bacon-qr-code/src/Exception/OutOfBoundsException.php
A	vendor/bacon/bacon-qr-code/src/Exception/RuntimeException.php
A	vendor/bacon/bacon-qr-code/src/Exception/UnexpectedValueException.php
A	vendor/bacon/bacon-qr-code/src/Exception/WriterException.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Color/Alpha.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Color/Cmyk.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Color/ColorInterface.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Color/Gray.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Color/Rgb.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Eye/CompositeEye.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Eye/EyeInterface.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Eye/ModuleEye.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Eye/SimpleCircleEye.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Eye/SquareEye.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Image/EpsImageBackEnd.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Image/ImageBackEndInterface.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Image/ImagickImageBackEnd.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Image/SvgImageBackEnd.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Image/TransformationMatrix.php
A	vendor/bacon/bacon-qr-code/src/Renderer/ImageRenderer.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Module/DotsModule.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Module/EdgeIterator/Edge.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Module/EdgeIterator/EdgeIterator.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Module/ModuleInterface.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Module/RoundnessModule.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Module/SquareModule.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Path/Close.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Path/Curve.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Path/EllipticArc.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Path/Line.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Path/Move.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Path/OperationInterface.php
A	vendor/bacon/bacon-qr-code/src/Renderer/Path/Path.php
A	vendor/bacon/bacon-qr-code/src/Renderer/PlainTextRenderer.php
A	vendor/bacon/bacon-qr-code/src/Renderer/RendererInterface.php
A	vendor/bacon/bacon-qr-code/src/Renderer/RendererStyle/EyeFill.php
A	vendor/bacon/bacon-qr-code/src/Renderer/RendererStyle/Fill.php
A	vendor/bacon/bacon-qr-code/src/Renderer/RendererStyle/Gradient.php
A	vendor/bacon/bacon-qr-code/src/Renderer/RendererStyle/GradientType.php
A	vendor/bacon/bacon-qr-code/src/Renderer/RendererStyle/RendererStyle.php
A	vendor/bacon/bacon-qr-code/src/Writer.php
A	vendor/bacon/bacon-qr-code/test/Common/BitArrayTest.php
A	vendor/bacon/bacon-qr-code/test/Common/BitMatrixTest.php
A	vendor/bacon/bacon-qr-code/test/Common/BitUtilsTest.php
A	vendor/bacon/bacon-qr-code/test/Common/ErrorCorrectionLevelTest.php
A	vendor/bacon/bacon-qr-code/test/Common/FormatInformationTest.php
A	vendor/bacon/bacon-qr-code/test/Common/ModeTest.php
A	vendor/bacon/bacon-qr-code/test/Common/ReedSolomonCodecTest.php
A	vendor/bacon/bacon-qr-code/test/Common/VersionTest.php
A	vendor/bacon/bacon-qr-code/test/Encoder/EncoderTest.php
A	vendor/bacon/bacon-qr-code/test/Encoder/MaskUtilTest.php
A	vendor/bacon/bacon-qr-code/test/Encoder/MatrixUtilTest.php
A	vendor/bacon/bacon-qr-code/test/Integration/ImagickRenderingTest.php
A	vendor/bacon/bacon-qr-code/test/Integration/__snapshots__/files/ImagickRenderingTest__testGenericQrCode__1.png
A	vendor/bacon/bacon-qr-code/test/Integration/__snapshots__/files/ImagickRenderingTest__testIssue79__1.png
M	vendor/composer/autoload_psr4.php
M	vendor/composer/autoload_real.php
M	vendor/composer/autoload_static.php
M	vendor/composer/installed.json
M	vendor/composer/installed.php
M	vendor/composer/platform_check.php
A	vendor/dasprid/enum/LICENSE
A	vendor/dasprid/enum/README.md
A	vendor/dasprid/enum/composer.json
A	vendor/dasprid/enum/src/AbstractEnum.php
A	vendor/dasprid/enum/src/EnumMap.php
A	vendor/dasprid/enum/src/Exception/CloneNotSupportedException.php
A	vendor/dasprid/enum/src/Exception/ExceptionInterface.php
A	vendor/dasprid/enum/src/Exception/ExpectationException.php
A	vendor/dasprid/enum/src/Exception/IllegalArgumentException.php
A	vendor/dasprid/enum/src/Exception/MismatchException.php
A	vendor/dasprid/enum/src/Exception/SerializeNotSupportedException.php
A	vendor/dasprid/enum/src/Exception/UnserializeNotSupportedException.php
A	vendor/dasprid/enum/src/NullValue.php
A	vendor/paragonie/constant_time_encoding/LICENSE.txt
A	vendor/paragonie/constant_time_encoding/README.md
A	vendor/paragonie/constant_time_encoding/composer.json
A	vendor/paragonie/constant_time_encoding/src/Base32.php
A	vendor/paragonie/constant_time_encoding/src/Base32Hex.php
A	vendor/paragonie/constant_time_encoding/src/Base64.php
A	vendor/paragonie/constant_time_encoding/src/Base64DotSlash.php
A	vendor/paragonie/constant_time_encoding/src/Base64DotSlashOrdered.php
A	vendor/paragonie/constant_time_encoding/src/Base64UrlSafe.php
A	vendor/paragonie/constant_time_encoding/src/Binary.php
A	vendor/paragonie/constant_time_encoding/src/EncoderInterface.php
A	vendor/paragonie/constant_time_encoding/src/Encoding.php
A	vendor/paragonie/constant_time_encoding/src/Hex.php
A	vendor/paragonie/constant_time_encoding/src/RFC4648.php
A	vendor/phpmailer/phpmailer/COMMITMENT
A	vendor/phpmailer/phpmailer/LICENSE
A	vendor/phpmailer/phpmailer/README.md
A	vendor/phpmailer/phpmailer/SECURITY.md
A	vendor/phpmailer/phpmailer/SMTPUTF8.md
A	vendor/phpmailer/phpmailer/VERSION
A	vendor/phpmailer/phpmailer/composer.json
A	vendor/phpmailer/phpmailer/get_oauth_token.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-af.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ar.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-as.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-az.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ba.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-be.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-bg.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-bn.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ca.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-cs.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-da.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-de.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-el.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-eo.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-es.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-et.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-fa.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-fi.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-fo.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-fr.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-gl.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-he.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-hi.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-hr.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-hu.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-hy.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-id.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-it.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ja.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ka.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ko.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ku.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-lt.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-lv.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-mg.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-mn.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ms.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-nb.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-nl.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-pl.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-pt.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-pt_br.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ro.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ru.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-si.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-sk.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-sl.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-sr.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-sr_latn.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-sv.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-tl.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-tr.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-uk.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-ur.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-vi.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-zh.php
A	vendor/phpmailer/phpmailer/language/phpmailer.lang-zh_cn.php
A	vendor/phpmailer/phpmailer/src/DSNConfigurator.php
A	vendor/phpmailer/phpmailer/src/Exception.php
A	vendor/phpmailer/phpmailer/src/OAuth.php
A	vendor/phpmailer/phpmailer/src/OAuthTokenProvider.php
A	vendor/phpmailer/phpmailer/src/PHPMailer.php
A	vendor/phpmailer/phpmailer/src/POP3.php
A	vendor/phpmailer/phpmailer/src/SMTP.php
A	vendor/pragmarx/google2fa/.github/workflows/run-tests.yml
A	vendor/pragmarx/google2fa/CHANGELOG.md
A	vendor/pragmarx/google2fa/LICENSE.md
A	vendor/pragmarx/google2fa/README.md
A	vendor/pragmarx/google2fa/composer.json
A	vendor/pragmarx/google2fa/src/Exceptions/Contracts/Google2FA.php
A	vendor/pragmarx/google2fa/src/Exceptions/Contracts/IncompatibleWithGoogleAuthenticator.php
A	vendor/pragmarx/google2fa/src/Exceptions/Contracts/InvalidAlgorithm.php
A	vendor/pragmarx/google2fa/src/Exceptions/Contracts/InvalidCharacters.php
A	vendor/pragmarx/google2fa/src/Exceptions/Contracts/SecretKeyTooShort.php
A	vendor/pragmarx/google2fa/src/Exceptions/Google2FAException.php
A	vendor/pragmarx/google2fa/src/Exceptions/IncompatibleWithGoogleAuthenticatorException.php
A	vendor/pragmarx/google2fa/src/Exceptions/InvalidAlgorithmException.php
A	vendor/pragmarx/google2fa/src/Exceptions/InvalidCharactersException.php
A	vendor/pragmarx/google2fa/src/Exceptions/SecretKeyTooShortException.php
A	vendor/pragmarx/google2fa/src/Google2FA.php
A	vendor/pragmarx/google2fa/src/Support/Base32.php
A	vendor/pragmarx/google2fa/src/Support/Constants.php
A	vendor/pragmarx/google2fa/src/Support/QRCode.php

commit 25f84f07ee55439cccbbfa98dda3a339c17f28c7	refs/remotes/origin/bruno_branch
Author: Bruno Costa <1231247@isep.ipp.pt>
Date:   Fri Jul 4 12:58:48 2025 +0100

    3

M	BLL/Colaborador/BLL_ficha_colaborador.php
M	BLL/Colaborador/BLL_inscricoes.php
A	BLL/Comuns/BLL_email.php
M	BLL/Comuns/BLL_perfil.php
M	BLL/Coordenador/BLL_dashboard_coordenador.php
M	BLL/RH/BLL_colaboradores_gerir.php
M	BLL/RH/BLL_dashboard_rh.php
M	BLL/RH/BLL_equipa_editar.php
M	BLL/RH/BLL_exportar.php
A	BLL/RH/BLL_formacoes_gerir.php
A	BLL/RH/BLL_gerir_beneficios.php
A	BLL/RH/BLL_recibos.php
A	BLL/RH/BLL_recibos_vencimento.php
M	DAL/Colaborador/DAL_ferias.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
M	DAL/RH/DAL_colaboradores_gerir.php
M	DAL/RH/DAL_dashboard_rh.php
M	DAL/RH/DAL_equipa_editar.php
M	DAL/RH/DAL_exportar.php
A	DAL/RH/DAL_gerir_beneficios.php
A	DAL/RH/DAL_gerir_formacoes.php
A	DAL/RH/DAL_recibos.php
A	DAL/RH/DAL_recibos_vencimento.php
M	UI/Colaborador/beneficios.php
M	UI/Colaborador/ferias.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Colaborador/formacoes.php
M	UI/Colaborador/pagina_inicial_colaborador.php
M	UI/Colaborador/pedir_ferias.php
M	UI/Colaborador/recibos.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Coordenador/dashboard_coordenador.php
M	UI/Coordenador/equipa.php
M	UI/Coordenador/pagina_inicial_coordenador.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipa.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
A	UI/RH/gerir_beneficios.php
A	UI/RH/gerir_formacoes.php
A	UI/RH/gerir_recibos.php
M	UI/RH/pagina_inicial_RH.php
M	UI/RH/relatorios.php
A	Uploads/Recibos/1231247062025.pdf
A	Uploads/Recibos/1231247072025.pdf
A	Uploads/Recibos/recibo_6_2025_6_1751543154.pdf
A	Uploads/Recibos/recibo_6_2025_6_1751543237.pdf
A	_doc/create_table_beneficios.sql
M	assets/CSS/Colaborador/ficha_colaborador.css
M	assets/CSS/Comuns/notificacoes.css
M	assets/CSS/Comuns/perfil.css
M	assets/CSS/Coordenador/dashboard_coordenador.css
M	assets/CSS/Coordenador/relatorios_equipa.css
M	assets/CSS/RH/colaborador_novo.css
M	assets/CSS/RH/colaboradores_gerir.css
M	assets/CSS/RH/dashboard_rh.css
M	assets/CSS/RH/equipa_editar.css
M	assets/CSS/RH/equipa_nova.css
M	assets/CSS/RH/equipas.css
M	assets/CSS/RH/exportar.css
A	assets/CSS/RH/gerir_beneficios.css
A	assets/CSS/RH/gerir_formacoes.css
A	assets/CSS/RH/gerir_recibos.css
M	assets/CSS/RH/pagina_inicial.css
M	assets/CSS/RH/relatorios.css

commit 22dba31ca5cf170d0a1c9ee2a7a80e490a62bd1f	refs/remotes/origin/bruno_branch
Author: Bruno Costa <1231247@isep.ipp.pt>
Date:   Fri Jul 4 12:47:06 2025 +0100

    Update relatorios.php

M	UI/RH/relatorios.php

commit 3a22a77c62073fbc50fdff77a0065b9b634fc5e9	refs/remotes/origin/bruno_branch
Author: Bruno Costa <1231247@isep.ipp.pt>
Date:   Thu Jul 3 17:34:05 2025 +0100

    2

M	BLL/RH/BLL_dashboard_rh.php
M	DAL/RH/DAL_dashboard_rh.php
M	assets/CSS/RH/dashboard_rh.css

commit c200e5fc4c523248c3f22ed9a5f1799cb6e760e9	refs/remotes/origin/bruno_branch
Author: Bruno Costa <1231247@isep.ipp.pt>
Date:   Thu Jul 3 17:16:04 2025 +0100

    1

A	BLL/Admin/BLL_alerta_novo.php
M	BLL/Admin/BLL_alertas.php
M	BLL/Authenticator.php
A	BLL/Colaborador/BLL_ferias.php
M	BLL/Colaborador/BLL_ficha_colaborador.php
A	BLL/Colaborador/BLL_formacoes.php
A	BLL/Colaborador/BLL_inscricoes.php
A	BLL/Colaborador/BLL_recibos_vencimento.php
A	BLL/Comuns/BLL_forgot_password.php
A	BLL/Comuns/BLL_mensagens.php
M	BLL/Comuns/BLL_notificacoes.php
M	BLL/Comuns/BLL_perfil.php
M	BLL/Coordenador/BLL_dashboard_coordenador.php
M	BLL/RH/BLL_dashboard_rh.php
A	BLL/RH/BLL_equipa_editar.php
A	BLL/RH/BLL_equipa_nova.php
M	BLL/RH/BLL_equipas.php
A	DAL/Admin/DAL_alerta_novo.php
M	DAL/Admin/DAL_alertas.php
A	DAL/Colaborador/DAL_ferias.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
A	DAL/Colaborador/DAL_formacoes.php
A	DAL/Colaborador/DAL_inscricoes.php
A	DAL/Colaborador/DAL_recibos_vencimento.php
A	DAL/Comuns/DAL_forgot_password.php
A	DAL/Comuns/DAL_mensagens.php
M	DAL/Comuns/DAL_notificacoes.php
M	DAL/Comuns/DAL_perfil.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
M	DAL/RH/DAL_colaboradores_gerir.php
M	DAL/RH/DAL_dashboard_rh.php
A	DAL/RH/DAL_equipa_editar.php
A	DAL/RH/DAL_equipa_nova.php
M	DAL/RH/DAL_equipas.php
A	Database/create_inscricao_formacoes.sql
A	Database/create_tables.sql
A	Database/fix_inscricoes_formacao.sql
M	GitAnalysis/Sprint_1/Commits.md
M	GitAnalysis/Sprint_1/Contributions.md
A	GitAnalysis/Sprint_2/Commits.md
A	GitAnalysis/Sprint_2/Contributions.md
A	UI/Admin/alerta_novo.php
A	UI/Colaborador/beneficios.php
A	UI/Colaborador/ferias.php
M	UI/Colaborador/ficha_colaborador.php
A	UI/Colaborador/formacoes.php
A	UI/Colaborador/inscrever_formacao.php
M	UI/Colaborador/pagina_inicial_colaborador.php
A	UI/Colaborador/pedir_ferias.php
A	UI/Colaborador/recibos.php
A	UI/Comuns/alterar_password.php
A	UI/Comuns/enviar_mensagem.php
A	UI/Comuns/forgot_password.php
M	UI/Comuns/login.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Coordenador/dashboard_coordenador.php
M	UI/Coordenador/equipa.php
A	UI/Coordenador/pagina_inicial_coordenador.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
A	UI/RH/equipa.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
A	Uploads/comprovativos/comprovativo_10_1750770302.pdf
A	Uploads/comprovativos/comprovativo_8_1750766087.pdf
A	Uploads/comprovativos/comprovativo_8_1750766690.pdf
A	Uploads/comprovativos/comprovativo_8_1750767197.pdf
A	Uploads/comprovativos/comprovativo_cartao_continente_15_1750843752.pdf
A	Uploads/comprovativos/comprovativo_cc_15_1750843752.pdf
A	Uploads/comprovativos/comprovativo_cc_8_1751542638.pdf
A	Uploads/comprovativos/comprovativo_cc_8_1751543005.pdf
A	Uploads/comprovativos/comprovativo_cc_8_1751543339.pdf
A	Uploads/comprovativos/comprovativo_cc_8_1751543406.pdf
A	Uploads/comprovativos/comprovativo_estado_civil_15_1750843752.pdf
A	Uploads/comprovativos/comprovativo_iban_15_1750843752.pdf
A	assets/CSS/Admin/alerta_novo.css
A	assets/CSS/Colaborador/beneficios.css
A	assets/CSS/Colaborador/ferias.css
M	assets/CSS/Colaborador/ficha_colaborador.css
A	assets/CSS/Colaborador/formacoes.css
M	assets/CSS/Colaborador/pagina_inicial_colaborador.css
A	assets/CSS/Colaborador/recibos_vencimento.css
A	assets/CSS/Comuns/forgot_password.css
M	assets/CSS/Comuns/notificacoes.css
M	assets/CSS/Comuns/perfil.css
M	assets/CSS/Coordenador/dashboard_coordenador.css
M	assets/CSS/Coordenador/equipa.css
A	assets/CSS/Coordenador/pagina_inicial_coordenador.css
M	assets/CSS/Coordenador/relatorios_equipa.css
M	assets/CSS/RH/colaborador_novo.css
M	assets/CSS/RH/dashboard_rh.css
A	assets/CSS/RH/equipa_editar.css
M	assets/CSS/RH/equipa_nova.css
M	assets/CSS/RH/equipas.css
A	composer.json
A	composer.lock
A	processar_alertas.php
A	vendor/autoload.php
A	vendor/composer/ClassLoader.php
A	vendor/composer/InstalledVersions.php
A	vendor/composer/LICENSE
A	vendor/composer/autoload_classmap.php
A	vendor/composer/autoload_namespaces.php
A	vendor/composer/autoload_psr4.php
A	vendor/composer/autoload_real.php
A	vendor/composer/autoload_static.php
A	vendor/composer/installed.json
A	vendor/composer/installed.php
A	vendor/composer/platform_check.php
</pre>

</details>

#### miguelscorreia24@gmail.com
<details>
<summary>Diff</summary>

<pre>
 0 files changed
</pre>
</details>
<details>
<summary>Commits</summary>

<pre>
</pre>

</details>

