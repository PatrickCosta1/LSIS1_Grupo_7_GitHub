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
commit 7436eb5bb2c78e3731fbb074f6e63f230eac9bbb	refs/remotes/origin/ana_branch (origin/ana_branch)
Author: Ana Ribeiro <1230654@isep.ipp.pt>
Date:   Sat Jun 28 15:02:11 2025 +0100

    criação das dashboards no coordenador

M	BLL/Colaborador/BLL_dashboard_colaborador.php
M	BLL/Coordenador/BLL_dashboard_coordenador.php
M	BLL/RH/BLL_equipas.php
M	DAL/Comuns/DAL_perfil.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
M	DAL/RH/DAL_colaboradores_gerir.php
M	DAL/RH/DAL_equipas.php
M	UI/Admin/alertas.php
M	UI/Admin/campos_personalizados.php
M	UI/Admin/dashboard_admin.php
M	UI/Admin/pagina_inicial_admin.php
M	UI/Admin/permissoes.php
M	UI/Admin/utilizadores.php
M	UI/Colaborador/dashboard_colaborador.php
M	UI/Colaborador/ficha_colaborador.php
A	UI/Colaborador/pagina_inicial_colaborador.php
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
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
A	UI/RH/pagina_inicial_RH.php
M	UI/RH/relatorios.php
A	assets/1.png
A	assets/2.png
A	assets/3.png
A	assets/4.png
A	assets/5.png
A	assets/6.png
M	assets/CSS/Colaborador/ficha_colaborador.css
A	assets/CSS/Colaborador/pagina_inicial_colaborador.css
M	assets/CSS/Comuns/login.css
M	assets/CSS/Comuns/notificacoes.css
M	assets/CSS/Comuns/perfil.css
M	assets/CSS/Coordenador/dashboard_coordenador.css
M	assets/CSS/Coordenador/equipa.css
A	assets/CSS/Coordenador/pagina_inicial_coordenador.css
M	assets/CSS/Coordenador/relatorios_equipa.css
M	assets/CSS/RH/colaborador_novo.css
M	assets/CSS/RH/dashboard_rh.css
M	assets/CSS/RH/equipa_nova.css
M	assets/CSS/RH/equipas.css
M	assets/CSS/RH/exportar.css
A	assets/CSS/RH/pagina_inicial.css
M	assets/CSS/RH/relatorios.css
A	assets/fundo.png
A	uploads/comprovativos/comprovativo_10_1750770302.pdf
A	uploads/comprovativos/comprovativo_8_1750766087.pdf
A	uploads/comprovativos/comprovativo_8_1750766690.pdf
A	uploads/comprovativos/comprovativo_8_1750767197.pdf
A	uploads/comprovativos/comprovativo_cartao_continente_15_1750843752.pdf
A	uploads/comprovativos/comprovativo_cc_15_1750843752.pdf
A	uploads/comprovativos/comprovativo_estado_civil_15_1750843752.pdf
A	uploads/comprovativos/comprovativo_iban_15_1750843752.pdf

commit 4ff6ab8058554356a4a51e7dadf4f1674b76730f	refs/remotes/origin/ana_branch
Author: Ana Ribeiro <1230654@isep.ipp.pt>
Date:   Fri Jun 27 11:52:16 2025 +0100

    criação de exemplos de dashboards para o rh

M	BLL/Admin/BLL_alertas.php
M	BLL/Admin/BLL_campos_personalizados.php
M	BLL/Admin/BLL_dashboard_admin.php
M	BLL/Admin/BLL_permissoes.php
M	BLL/Admin/BLL_utilizadores.php
M	BLL/Colaborador/BLL_dashboard_colaborador.php
M	BLL/Colaborador/BLL_ficha_colaborador.php
M	BLL/Comuns/BLL_notificacoes.php
M	BLL/Comuns/BLL_perfil.php
M	BLL/Convidado/BLL_dashboard_convidado.php
M	BLL/Convidado/BLL_onboarding_convidado.php
M	BLL/Coordenador/BLL_dashboard_coordenador.php
M	BLL/RH/BLL_colaboradores_gerir.php
M	BLL/RH/BLL_dashboard_rh.php
M	BLL/RH/BLL_equipas.php
M	BLL/RH/BLL_exportar.php
M	BLL/RH/BLL_relatorios.php
M	DAL/Admin/DAL_alertas.php
M	DAL/Admin/DAL_campos_personalizados.php
M	DAL/Admin/DAL_dashboard_admin.php
M	DAL/Admin/DAL_permissoes.php
M	DAL/Admin/DAL_utilizadores.php
M	DAL/Colaborador/DAL_dashboard_colaborador.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
M	DAL/Comuns/DAL_login.php
M	DAL/Comuns/DAL_notificacoes.php
M	DAL/Comuns/DAL_perfil.php
M	DAL/Convidado/DAL_dashboard_convidado.php
M	DAL/Convidado/DAL_onboarding_convidado.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
M	DAL/RH/DAL_colaboradores_gerir.php
M	DAL/RH/DAL_dashboard_rh.php
M	DAL/RH/DAL_equipas.php
M	DAL/RH/DAL_exportar.php
M	DAL/RH/DAL_relatorios.php
M	UI/Admin/alertas.php
M	UI/Admin/campos_personalizados.php
M	UI/Admin/dashboard_admin.php
A	UI/Admin/pagina_inicial_admin.php
M	UI/Admin/permissoes.php
M	UI/Admin/utilizador_editar.php
M	UI/Admin/utilizador_novo.php
M	UI/Admin/utilizador_remover.php
M	UI/Admin/utilizadores.php
M	UI/Colaborador/dashboard_colaborador.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Comuns/erro.php
M	UI/Comuns/login.php
M	UI/Comuns/logout.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Convidado/dashboard_convidado.php
M	UI/Convidado/onboarding_convidado.php
M	UI/Coordenador/dashboard_coordenador.php
M	UI/Coordenador/equipa.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
M	UI/RH/relatorios.php
A	assets/CSS/Admin/alertas.css
A	assets/CSS/Admin/base.css
A	assets/CSS/Admin/campos.css
A	assets/CSS/Admin/dashboard.css
A	assets/CSS/Admin/utilizadores.css
A	assets/CSS/Colaborador/dashboard_colaborador.css
A	assets/CSS/Colaborador/ficha_colaborador.css
R100	DAL/Colaborador/BLL_dashboard_colaborador.php	assets/CSS/Comuns/erro.css
A	assets/CSS/Comuns/login.css
R100	DAL/Colaborador/BLL_ficha_colaborador.php	assets/CSS/Comuns/logout.css
A	assets/CSS/Comuns/notificacoes.css
A	assets/CSS/Comuns/perfil.css
A	assets/CSS/Coordenador/dashboard_coordenador.css
A	assets/CSS/Coordenador/equipa.css
A	assets/CSS/Coordenador/relatorios_equipa.css
A	assets/CSS/RH/colaborador_novo.css
A	assets/CSS/RH/colaboradores_gerir.css
A	assets/CSS/RH/dashboard_rh.css
A	assets/CSS/RH/equipa_nova.css
A	assets/CSS/RH/equipas.css
A	assets/CSS/RH/exportar.css
A	assets/CSS/RH/relatorios.css
D	assets/style.css
D	assets/styles.css
D	assets/teste.css
A	assets/tlantic-logo-escuro.png
A	assets/tlantic-logo2.png
M	index.php

commit 55724ac48376ac1082653b143c35e828804ee178	refs/remotes/origin/ana_branch
Author: Ana Ribeiro <1230654@isep.ipp.pt>
Date:   Wed Jun 25 11:46:38 2025 +0100

    Update BLL_permissoes.php

M	BLL/Admin/BLL_permissoes.php

commit 3f761de0b968ff692781fbca4b1394aa99416dd1	refs/remotes/origin/ana_branch
Author: Ana Ribeiro <1230654@isep.ipp.pt>
Date:   Wed Jun 25 11:46:01 2025 +0100

    Update BLL_permissoes.php

M	BLL/Admin/BLL_permissoes.php

commit 69de90834381b81e45d457234453a01709e03fa6	refs/remotes/origin/ana_branch
Author: Ana Ribeiro <1230654@isep.ipp.pt>
Date:   Wed Jun 25 10:24:37 2025 +0100

    Update permissoes.php

M	UI/Admin/permissoes.php

commit 5b695c7d7a64b35b5c72dbb0a8ba638449930acb	refs/remotes/origin/ana_branch
Author: Ana Ribeiro <1230654@isep.ipp.pt>
Date:   Tue Jun 24 16:56:57 2025 +0100

    Update login.php

M	UI/Comuns/login.php

commit 29dcc5304bbf6026454b00659ab13fd8baca0b56	refs/remotes/origin/ana_branch
Author: Ana Ribeiro <1230654@isep.ipp.pt>
Date:   Tue Jun 24 16:55:59 2025 +0100

    Update login.php

M	UI/Comuns/login.php

commit 2dfb2b4d84d73841013f12ceef250df3e919a793	refs/remotes/origin/ana_branch
Author: Ana Ribeiro <1230654@isep.ipp.pt>
Date:   Tue Jun 24 16:53:34 2025 +0100

    Update login.php

M	UI/Comuns/login.php

commit 8b064138c329c3b8ef93f65542391f5ba6b2ba12	refs/remotes/origin/ana_branch
Author: Ana Ribeiro <1230654@isep.ipp.pt>
Date:   Tue Jun 24 16:52:39 2025 +0100

    Update login.php

M	UI/Comuns/login.php

commit b1f6385ed5554756b005a61e49d5238f6840cbfb	refs/remotes/origin/ana_branch
Author: Ana Ribeiro <1230654@isep.ipp.pt>
Date:   Tue Jun 24 16:24:19 2025 +0100

    Update login.php

M	UI/Comuns/login.php

commit fcefe3b28bdd6e1b5a0c6694ce44fdde447b7f25	refs/remotes/origin/ana_branch
Author: Ana Ribeiro <1230654@isep.ipp.pt>
Date:   Tue Jun 24 16:24:04 2025 +0100

    Update login.php

M	UI/Comuns/login.php
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
commit 1c8950c8306cb18b809088270183e7cf5e103744	refs/remotes/origin/patrick_branch (origin/patrick_branch)
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Fri Jun 27 12:31:25 2025 +0100

    Menu coordenador funcional

M	BLL/Coordenador/BLL_dashboard_coordenador.php
M	BLL/RH/BLL_dashboard_rh.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
M	DAL/RH/DAL_colaboradores_gerir.php
M	DAL/RH/DAL_dashboard_rh.php
M	UI/Admin/alertas.php
M	UI/Admin/campos_personalizados.php
M	UI/Admin/permissoes.php
M	UI/Admin/utilizadores.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Colaborador/pagina_inicial_colaborador.php
M	UI/Comuns/login.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Coordenador/dashboard_coordenador.php
M	UI/RH/colaborador_novo.php
M	UI/RH/dashboard_rh.php
M	assets/CSS/Coordenador/dashboard_coordenador.css
M	assets/CSS/Coordenador/equipa.css
M	assets/CSS/Coordenador/relatorios_equipa.css
M	assets/CSS/RH/colaborador_novo.css
M	assets/CSS/RH/dashboard_rh.css
M	assets/CSS/RH/relatorios.css

commit 5e7c185b57285e9ebb687e1621cfa6480116c474	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Fri Jun 27 10:47:51 2025 +0100

    COORDENADOR

M	UI/Coordenador/dashboard_coordenador.php
M	UI/Coordenador/equipa.php

commit 393be87328fc65250a95ae8e32336463c320e23f	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Fri Jun 27 10:17:57 2025 +0100

    Funções Coordenador

M	BLL/Colaborador/BLL_dashboard_colaborador.php
M	BLL/RH/BLL_equipas.php
M	DAL/Comuns/DAL_perfil.php
M	DAL/RH/DAL_equipas.php
M	UI/Admin/alertas.php
M	UI/Admin/campos_personalizados.php
M	UI/Admin/dashboard_admin.php
A	UI/Admin/pagina_inicial_admin.php
M	UI/Admin/permissoes.php
M	UI/Admin/utilizador_editar.php
M	UI/Admin/utilizador_novo.php
M	UI/Admin/utilizadores.php
M	UI/Colaborador/dashboard_colaborador.php
M	UI/Colaborador/ficha_colaborador.php
A	UI/Colaborador/pagina_inicial_colaborador.php
M	UI/Comuns/erro.php
M	UI/Comuns/login.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Convidado/dashboard_convidado.php
M	UI/Convidado/onboarding_convidado.php
M	UI/Coordenador/dashboard_coordenador.php
M	UI/Coordenador/equipa.php
A	UI/Coordenador/pagina_inicial_coordenador.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
A	UI/RH/pagina_inicial_RH.php
M	UI/RH/relatorios.php
A	assets/1.png
A	assets/2.png
A	assets/3.png
A	assets/4.png
A	assets/5.png
A	assets/6.png
A	assets/CSS/Admin/alertas.css
A	assets/CSS/Admin/base.css
A	assets/CSS/Admin/campos.css
A	assets/CSS/Admin/dashboard.css
A	assets/CSS/Admin/utilizadores.css
M	assets/CSS/Colaborador/dashboard_colaborador.css
M	assets/CSS/Colaborador/ficha_colaborador.css
A	assets/CSS/Colaborador/pagina_inicial_colaborador.css
M	assets/CSS/Comuns/login.css
M	assets/CSS/Comuns/notificacoes.css
M	assets/CSS/Comuns/perfil.css
M	assets/CSS/Coordenador/dashboard_coordenador.css
M	assets/CSS/Coordenador/equipa.css
A	assets/CSS/Coordenador/pagina_inicial_coordenador.css
M	assets/CSS/RH/colaboradores_gerir.css
M	assets/CSS/RH/dashboard_rh.css
M	assets/CSS/RH/equipa_nova.css
M	assets/CSS/RH/equipas.css
M	assets/CSS/RH/exportar.css
A	assets/CSS/RH/pagina_inicial.css
M	assets/CSS/RH/relatorios.css
A	assets/fundo.png
A	assets/tlantic-logo-escuro.png
M	index.php

commit 490a8f8998dbadc805e710e2c317397535ec922f	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Wed Jun 25 22:34:09 2025 +0100

    Estilos remodelados e estruturados
    
    Estilos css remodelados e estruturados por pagina php
    falta admin pois tem que se ver o que ele pode fazer ao certo

M	BLL/Admin/BLL_alertas.php
M	BLL/Admin/BLL_campos_personalizados.php
M	BLL/Admin/BLL_dashboard_admin.php
M	BLL/Admin/BLL_permissoes.php
M	BLL/Admin/BLL_utilizadores.php
M	BLL/Colaborador/BLL_dashboard_colaborador.php
M	BLL/Colaborador/BLL_ficha_colaborador.php
M	BLL/Comuns/BLL_notificacoes.php
M	BLL/Comuns/BLL_perfil.php
M	BLL/Convidado/BLL_dashboard_convidado.php
M	BLL/Convidado/BLL_onboarding_convidado.php
M	BLL/Coordenador/BLL_dashboard_coordenador.php
M	BLL/RH/BLL_colaboradores_gerir.php
M	BLL/RH/BLL_dashboard_rh.php
M	BLL/RH/BLL_equipas.php
M	BLL/RH/BLL_exportar.php
M	BLL/RH/BLL_relatorios.php
M	DAL/Admin/DAL_alertas.php
M	DAL/Admin/DAL_campos_personalizados.php
M	DAL/Admin/DAL_dashboard_admin.php
M	DAL/Admin/DAL_permissoes.php
M	DAL/Admin/DAL_utilizadores.php
D	DAL/Colaborador/DAL_campos_personalizados.php
M	DAL/Colaborador/DAL_dashboard_colaborador.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
M	DAL/Comuns/DAL_login.php
M	DAL/Comuns/DAL_notificacoes.php
M	DAL/Comuns/DAL_perfil.php
M	DAL/Convidado/DAL_dashboard_convidado.php
M	DAL/Convidado/DAL_onboarding_convidado.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
M	DAL/RH/DAL_dashboard_rh.php
M	DAL/RH/DAL_equipas.php
M	DAL/RH/DAL_exportar.php
M	DAL/RH/DAL_relatorios.php
M	UI/Admin/alertas.php
M	UI/Admin/campos_personalizados.php
M	UI/Admin/dashboard_admin.php
M	UI/Admin/permissoes.php
M	UI/Admin/utilizador_editar.php
M	UI/Admin/utilizador_novo.php
M	UI/Admin/utilizadores.php
M	UI/Colaborador/dashboard_colaborador.php
M	UI/Colaborador/ficha_colaborador.php
D	UI/Comuns/dashboard_global.php
M	UI/Comuns/erro.php
M	UI/Comuns/login.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Convidado/dashboard_convidado.php
M	UI/Convidado/onboarding_convidado.php
M	UI/Coordenador/dashboard_coordenador.php
M	UI/Coordenador/equipa.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
D	UI/RH/equipa_editar.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
M	UI/RH/relatorios.php
A	assets/CSS/Colaborador/dashboard_colaborador.css
A	assets/CSS/Colaborador/ficha_colaborador.css
R100	BLL/Colaborador/BLL_campos_personalizados.php	assets/CSS/Comuns/erro.css
A	assets/CSS/Comuns/login.css
R100	DAL/Colaborador/BLL_dashboard_colaborador.php	assets/CSS/Comuns/logout.css
A	assets/CSS/Comuns/notificacoes.css
A	assets/CSS/Comuns/perfil.css
A	assets/CSS/Coordenador/dashboard_coordenador.css
A	assets/CSS/Coordenador/equipa.css
A	assets/CSS/Coordenador/relatorios_equipa.css
A	assets/CSS/RH/colaborador_novo.css
A	assets/CSS/RH/colaboradores_gerir.css
A	assets/CSS/RH/dashboard_rh.css
A	assets/CSS/RH/equipa_nova.css
A	assets/CSS/RH/equipas.css
A	assets/CSS/RH/exportar.css
A	assets/CSS/RH/relatorios.css
D	assets/menu_notificacoes.css
D	assets/style.css
D	assets/styles.css
D	assets/teste.css
A	assets/tlantic-logo2.png
D	dashboard.html

commit 97c2ca68399f883841ddd06d25e0e3dfe2f91206	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Wed Jun 25 20:42:31 2025 +0100

    Teste dashboard(nao considerar)

M	BLL/Colaborador/BLL_ficha_colaborador.php
M	BLL/Coordenador/BLL_dashboard_coordenador.php
M	BLL/RH/BLL_relatorios.php
D	DAL/Colaborador/BLL_ficha_colaborador.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
M	DAL/RH/DAL_relatorios.php
A	UI/Comuns/dashboard_global.php
M	UI/Coordenador/dashboard_coordenador.php
M	UI/Coordenador/equipa.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/relatorios.php
A	dashboard.html
A	uploads/comprovativos/comprovativo_cartao_continente_15_1750843752.pdf
A	uploads/comprovativos/comprovativo_cc_15_1750843752.pdf
A	uploads/comprovativos/comprovativo_estado_civil_15_1750843752.pdf
A	uploads/comprovativos/comprovativo_iban_15_1750843752.pdf

commit 60277d84362512584b2aa574dda78110c1020ffc	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Wed Jun 25 10:18:40 2025 +0100

    10h18

M	BLL/Colaborador/BLL_ficha_colaborador.php
M	UI/Colaborador/ficha_colaborador.php

commit 6162b601e71703620c3b24e33c8bf2efed8b2071	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Wed Jun 25 10:14:39 2025 +0100

    25/06 10h14

M	DAL/Colaborador/BLL_ficha_colaborador.php
M	DAL/RH/DAL_colaboradores_gerir.php
M	DAL/RH/DAL_equipas.php
M	UI/Admin/alertas.php
M	UI/Admin/utilizador_editar.php
M	UI/Admin/utilizador_novo.php
M	UI/Colaborador/dashboard_colaborador.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Comuns/perfil.php
M	UI/Convidado/dashboard_convidado.php
M	UI/Convidado/onboarding_convidado.php
M	UI/Coordenador/dashboard_coordenador.php
M	UI/Coordenador/equipa.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/equipa_editar.php
M	UI/RH/equipas.php

commit 9ac664e607d5047bdb7448a3c7c0006df01394dc	refs/remotes/origin/patrick_branch
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Tue Jun 24 16:37:34 2025 +0100

    24/06 16h36
    
    Funcões de colaborador completas
    Funções de coordenador por ffazer: graficos(dashboard) e relatorios

M	BLL/Admin/BLL_alertas.php
M	BLL/Admin/BLL_campos_personalizados.php
M	BLL/Admin/BLL_utilizadores.php
M	BLL/Colaborador/BLL_campos_personalizados.php
M	BLL/Colaborador/BLL_ficha_colaborador.php
M	BLL/RH/BLL_colaboradores_gerir.php
M	BLL/RH/BLL_equipas.php
M	DAL/Admin/DAL_alertas.php
M	DAL/Admin/DAL_campos_personalizados.php
M	DAL/Admin/DAL_utilizadores.php
M	DAL/Colaborador/DAL_campos_personalizados.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
M	DAL/RH/DAL_colaboradores_gerir.php
M	DAL/RH/DAL_equipas.php
D	UI/Admin/alerta_novo.php
M	UI/Admin/alertas.php
D	UI/Admin/campo_novo.php
M	UI/Admin/campos_personalizados.php
M	UI/Admin/dashboard_admin.php
M	UI/Admin/permissoes.php
M	UI/Admin/utilizador_editar.php
M	UI/Admin/utilizador_novo.php
M	UI/Admin/utilizador_remover.php
M	UI/Admin/utilizadores.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Comuns/logout.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Coordenador/dashboard_coordenador.php
M	UI/Coordenador/equipa.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php
D	UI/RH/equipa_colaboradores.php
D	UI/RH/equipa_coordenador.php
A	UI/RH/equipa_editar.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
M	UI/RH/relatorios.php
A	assets/menu_notificacoes.css
M	assets/style.css
A	uploads/comprovativos/comprovativo_10_1750770302.pdf
A	uploads/comprovativos/comprovativo_8_1750766087.pdf
A	uploads/comprovativos/comprovativo_8_1750766690.pdf
A	uploads/comprovativos/comprovativo_8_1750767197.pdf
</pre>

</details>

#### 1231245@isep.ipp.pt
<details>
<summary>Diff</summary>

<pre>
 /Colaborador/BLL_ficha_colaborador.php                  |   22 
 /UI/Admin/pagina_inicial_admin.php                      |   90 
 /UI/Colaborador/pagina_inicial_colaborador.php          |   65 
 /UI/Coordenador/pagina_inicial_coordenador.php          |  110 
 /assets/CSS/Admin/base.css                              |  111 
 /assets/CSS/Colaborador/dashboard_colaborador.css       |  233 +
 /assets/CSS/Colaborador/ficha_colaborador.css           |  710 +++++
 /assets/CSS/Colaborador/pagina_inicial_colaborador.css  |  126 +
 /assets/CSS/Comuns/login.css                            |  238 +
 /assets/CSS/Comuns/notificacoes.css                     |  253 ++
 /assets/CSS/Comuns/perfil.css                           |  169 +
 /assets/CSS/Coordenador/dashboard_coordenador.css       |  233 +
 /assets/CSS/Coordenador/equipa.css                      |  214 +
 /assets/CSS/Coordenador/pagina_inicial_coordenador.css  |  290 ++
 /assets/CSS/Coordenador/relatorios_equipa.css           |  206 +
 /assets/CSS/RH/colaborador_novo.css                     |  301 ++
 /assets/CSS/RH/colaboradores_gerir.css                  |  289 ++
 /assets/CSS/RH/dashboard_rh.css                         |  237 +
 /assets/CSS/RH/equipa_nova.css                          |  285 ++
 /assets/CSS/RH/equipas.css                              |  291 ++
 /assets/CSS/RH/exportar.css                             |  250 ++
 /assets/CSS/RH/relatorios.css                           |  276 ++
 /dev/null                                               |binary
 /style.css                                              |   43 
 /styles.css                                             |    2 
 BLL/Colaborador/BLL_dashboard_colaborador.php           |    1 
 BLL/Coordenador/BLL_dashboard_coordenador.php           |    2 
 BLL/RH/BLL_dashboard_rh.php                             |    1 
 BLL/RH/BLL_equipas.php                                  |    1 
 DAL/Colaborador/BLL_ficha_colaborador.php               |   22 
 DAL/Colaborador/DAL_ficha_colaborador.php               |   31 
 DAL/Comuns/DAL_perfil.php                               |    1 
 DAL/Coordenador/DAL_dashboard_coordenador.php           |    4 
 DAL/RH/DAL_colaboradores_gerir.php                      |    4 
 DAL/RH/DAL_dashboard_rh.php                             |    1 
 DAL/RH/DAL_equipas.php                                  |    1 
 UI/Admin/alertas.php                                    |   18 
 UI/Admin/campos_personalizados.php                      |   94 
 UI/Admin/dashboard_admin.php                            |   34 
 UI/Admin/pagina_inicial_admin.php                       |    4 
 UI/Admin/permissoes.php                                 |   26 
 UI/Admin/utilizador_editar.php                          |    1 
 UI/Admin/utilizador_novo.php                            |    1 
 UI/Admin/utilizadores.php                               |   38 
 UI/Colaborador/dashboard_colaborador.php                |   30 
 UI/Colaborador/ficha_colaborador.php                    | 1923 +++++++---!!!!!
 UI/Colaborador/pagina_inicial_colaborador.php           |   65 
 UI/Comuns/erro.php                                      |    1 
 UI/Comuns/login.php                                     |    8 
 UI/Comuns/notificacoes.php                              |  137 
 UI/Comuns/perfil.php                                    |  162 
 UI/Convidado/dashboard_convidado.php                    |    1 
 UI/Convidado/onboarding_convidado.php                   |    1 
 UI/Coordenador/dashboard_coordenador.php                |   85 
 UI/Coordenador/equipa.php                               |   11 
 UI/Coordenador/relatorios_equipa.php                    |   14 
 UI/RH/colaborador_novo.php                              |    9 
 UI/RH/colaboradores_gerir.php                           |    9 
 UI/RH/dashboard_rh.php                                  |  355 ++
 UI/RH/equipa_nova.php                                   |    5 
 UI/RH/equipas.php                                       |   89 
 UI/RH/exportar.php                                      |    3 
 UI/RH/relatorios.php                                    |    4 
 assets/CSS/Colaborador/ficha_colaborador.css            |  313 +
 assets/CSS/Colaborador/pagina_inicial_colaborador.css   |  175 +
 assets/CSS/Comuns/login.css                             |   18 
 assets/CSS/Comuns/notificacoes.css                      |  162 -
 assets/CSS/Comuns/perfil.css                            |  110 
 assets/CSS/Coordenador/equipa.css                       |    2 
 assets/CSS/Coordenador/relatorios_equipa.css            |    1 
 assets/CSS/RH/colaboradores_gerir.css                   |  132 !
 assets/CSS/RH/dashboard_rh.css                          |  186 !
 assets/CSS/RH/equipas.css                               |   71 
 assets/CSS/RH/relatorios.css                            |  232 !
 assets/style.css                                        |  252 --
 assets/styles.css                                       |  168 -
 assets/teste.css                                        |  104 
 b/BLL/Admin/BLL_alertas.php                             |    1 
 b/BLL/Admin/BLL_campos_personalizados.php               |    1 
 b/BLL/Admin/BLL_dashboard_admin.php                     |    1 
 b/BLL/Admin/BLL_permissoes.php                          |    1 
 b/BLL/Admin/BLL_utilizadores.php                        |    1 
 b/BLL/Colaborador/BLL_dashboard_colaborador.php         |   10 
 b/BLL/Colaborador/BLL_ficha_colaborador.php             |    1 
 b/BLL/Comuns/BLL_mensagens.php                          |   21 
 b/BLL/Comuns/BLL_notificacoes.php                       |    1 
 b/BLL/Comuns/BLL_perfil.php                             |    1 
 b/BLL/Convidado/BLL_dashboard_convidado.php             |    1 
 b/BLL/Convidado/BLL_onboarding_convidado.php            |    1 
 b/BLL/Coordenador/BLL_dashboard_coordenador.php         |   16 
 b/BLL/RH/BLL_colaboradores_gerir.php                    |    1 
 b/BLL/RH/BLL_dashboard_rh.php                           |   20 
 b/BLL/RH/BLL_equipas.php                                |    5 
 b/BLL/RH/BLL_exportar.php                               |    1 
 b/BLL/RH/BLL_relatorios.php                             |    1 
 b/DAL/Admin/DAL_alertas.php                             |    1 
 b/DAL/Admin/DAL_campos_personalizados.php               |    1 
 b/DAL/Admin/DAL_dashboard_admin.php                     |    1 
 b/DAL/Admin/DAL_permissoes.php                          |    1 
 b/DAL/Admin/DAL_utilizadores.php                        |    1 
 b/DAL/Colaborador/DAL_dashboard_colaborador.php         |    1 
 b/DAL/Colaborador/DAL_ficha_colaborador.php             |    1 
 b/DAL/Comuns/DAL_login.php                              |    1 
 b/DAL/Comuns/DAL_mensagens.php                          |   32 
 b/DAL/Comuns/DAL_notificacoes.php                       |    1 
 b/DAL/Comuns/DAL_perfil.php                             |   11 
 b/DAL/Convidado/DAL_dashboard_convidado.php             |    1 
 b/DAL/Convidado/DAL_onboarding_convidado.php            |    1 
 b/DAL/Coordenador/DAL_dashboard_coordenador.php         |  101 
 b/DAL/RH/DAL_colaboradores_gerir.php                    |   15 
 b/DAL/RH/DAL_dashboard_rh.php                           |   60 
 b/DAL/RH/DAL_equipas.php                                |   32 
 b/DAL/RH/DAL_exportar.php                               |    1 
 b/DAL/RH/DAL_relatorios.php                             |    1 
 b/UI/Admin/alertas.php                                  |    1 
 b/UI/Admin/campos_personalizados.php                    |    1 
 b/UI/Admin/dashboard_admin.php                          |    1 
 b/UI/Admin/pagina_inicial_admin.php                     |    1 
 b/UI/Admin/permissoes.php                               |    1 
 b/UI/Admin/utilizador_editar.php                        |   96 
 b/UI/Admin/utilizador_novo.php                          |  147 +
 b/UI/Admin/utilizador_remover.php                       |    1 
 b/UI/Admin/utilizadores.php                             |    1 
 b/UI/Colaborador/dashboard_colaborador.php              |    8 
 b/UI/Colaborador/ficha_colaborador.php                  |  391 +!!
 b/UI/Colaborador/pagina_inicial_colaborador.php         |    2 
 b/UI/Comuns/enviar_mensagem.php                         |   44 
 b/UI/Comuns/erro.php                                    |    1 
 b/UI/Comuns/login.php                                   |    1 
 b/UI/Comuns/logout.php                                  |    1 
 b/UI/Comuns/notificacoes.php                            |   52 
 b/UI/Comuns/perfil.php                                  |   39 
 b/UI/Convidado/dashboard_convidado.php                  |    1 
 b/UI/Convidado/onboarding_convidado.php                 |    1 
 b/UI/Coordenador/dashboard_coordenador.php              |  450 +++
 b/UI/Coordenador/equipa.php                             |  131 !
 b/UI/Coordenador/pagina_inicial_coordenador.php         |   29 
 b/UI/Coordenador/relatorios_equipa.php                  |   71 
 b/UI/RH/colaborador_novo.php                            |   83 
 b/UI/RH/colaboradores_gerir.php                         |    1 
 b/UI/RH/dashboard_rh.php                                |  128 
 b/UI/RH/equipa_nova.php                                 |   18 
 b/UI/RH/equipas.php                                     |    5 
 b/UI/RH/exportar.php                                    |    1 
 b/UI/RH/pagina_inicial_RH.php                           |   67 
 b/UI/RH/relatorios.php                                  |   29 
 b/assets/1.png                                          |binary
 b/assets/2.png                                          |binary
 b/assets/3.png                                          |binary
 b/assets/4.png                                          |binary
 b/assets/5.png                                          |binary
 b/assets/6.png                                          |binary
 b/assets/CSS/Admin/alertas.css                          |   57 
 b/assets/CSS/Admin/base.css                             |    4 
 b/assets/CSS/Admin/campos.css                           |   40 
 b/assets/CSS/Admin/dashboard.css                        |   83 
 b/assets/CSS/Admin/utilizadores.css                     |   58 
 b/assets/CSS/Colaborador/dashboard_colaborador.css      |   47 
 b/assets/CSS/Colaborador/ficha_colaborador.css          |  129 +
 b/assets/CSS/Colaborador/pagina_inicial_colaborador.css |   34 
 b/assets/CSS/Comuns/login.css                           |    1 
 b/assets/CSS/Comuns/notificacoes.css                    |   52 
 b/assets/CSS/Comuns/perfil.css                          |   53 
 b/assets/CSS/Coordenador/dashboard_coordenador.css      |  332 ++
 b/assets/CSS/Coordenador/equipa.css                     |  390 ++-
 b/assets/CSS/Coordenador/pagina_inicial_coordenador.css |   53 
 b/assets/CSS/Coordenador/relatorios_equipa.css          |  243 +
 b/assets/CSS/RH/colaborador_novo.css                    |  181 !
 b/assets/CSS/RH/colaboradores_gerir.css                 |   55 
 b/assets/CSS/RH/dashboard_rh.css                        |  191 +
 b/assets/CSS/RH/equipa_nova.css                         |   81 
 b/assets/CSS/RH/equipas.css                             |    2 
 b/assets/CSS/RH/exportar.css                            |   52 
 b/assets/CSS/RH/pagina_inicial.css                      |  163 +
 b/assets/CSS/RH/relatorios.css                          |   13 
 b/assets/fundo.png                                      |binary
 b/assets/tlantic-logo-escuro.png                        |binary
 b/assets/tlantic-logo.png                               |binary
 b/assets/tlantic-logo2.png                              |binary
 b/index.php                                             |    1 
 index.php                                               |    2 
 181 files changed, 10325 insertions(+), 1998 deletions(-), 2314 modifications(!)
</pre>
</details>
<details>
<summary>Commits</summary>

<pre>
commit 2124a3b1ba327b5dab8de84941a5c5bfdf23b229	refs/remotes/origin/miguel_branch (origin/miguel_branch)
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Sun Jun 29 04:50:13 2025 +0100

    Mudanças
    
    Várias alterações, desde design, permissões e introdução de novas funcionalidades

A	BLL/Comuns/BLL_mensagens.php
M	BLL/Coordenador/BLL_dashboard_coordenador.php
A	DAL/Comuns/DAL_mensagens.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
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

commit 25ccbaff0047298c15274544853b12b4d8ae4dd6	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Fri Jun 27 12:12:17 2025 +0100

    coordenador updates

M	BLL/Coordenador/BLL_dashboard_coordenador.php
M	BLL/RH/BLL_dashboard_rh.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
M	DAL/RH/DAL_colaboradores_gerir.php
M	DAL/RH/DAL_dashboard_rh.php
M	UI/Colaborador/pagina_inicial_colaborador.php
M	UI/Comuns/login.php
M	UI/Coordenador/dashboard_coordenador.php
M	UI/Coordenador/equipa.php
A	UI/Coordenador/pagina_inicial_coordenador.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/colaborador_novo.php
M	UI/RH/dashboard_rh.php
M	assets/CSS/Coordenador/equipa.css
A	assets/CSS/Coordenador/pagina_inicial_coordenador.css
M	assets/CSS/Coordenador/relatorios_equipa.css
M	assets/CSS/RH/colaborador_novo.css
M	assets/CSS/RH/dashboard_rh.css
M	assets/CSS/RH/relatorios.css

commit f3d3a2d61c8bdb1c3904fb8ecb79db53f8a25af8	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Fri Jun 27 09:19:15 2025 +0100

    css ficha colaborador

M	UI/Colaborador/ficha_colaborador.php
M	UI/Colaborador/pagina_inicial_colaborador.php
M	UI/Comuns/perfil.php
M	assets/CSS/Colaborador/ficha_colaborador.css
M	assets/CSS/Colaborador/pagina_inicial_colaborador.css

commit 85abc965426a6b50fe31e7aa297e53d43ec9db52	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Fri Jun 27 02:27:17 2025 +0100

    mudanças na estilização, permissões e files do colaborador

M	UI/Colaborador/dashboard_colaborador.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Colaborador/pagina_inicial_colaborador.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
A	assets/1.png
A	assets/2.png
A	assets/3.png
A	assets/4.png
A	assets/5.png
A	assets/6.png
M	assets/CSS/Colaborador/ficha_colaborador.css
M	assets/CSS/Colaborador/pagina_inicial_colaborador.css
M	assets/CSS/Comuns/notificacoes.css
M	assets/CSS/Comuns/perfil.css

commit f46ff599f0917dfe5a1603b761b16862a2e40983	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Thu Jun 26 22:17:19 2025 +0100

    css
    
    Várias mudanças nos estilos de várias páginas.

M	BLL/Colaborador/BLL_dashboard_colaborador.php
M	BLL/RH/BLL_equipas.php
M	DAL/Comuns/DAL_perfil.php
M	DAL/RH/DAL_equipas.php
M	UI/Admin/alertas.php
M	UI/Admin/campos_personalizados.php
M	UI/Admin/dashboard_admin.php
M	UI/Admin/pagina_inicial_admin.php
M	UI/Admin/permissoes.php
M	UI/Admin/utilizadores.php
M	UI/Colaborador/dashboard_colaborador.php
A	UI/Colaborador/pagina_inicial_colaborador.php
M	UI/Comuns/login.php
M	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
A	UI/RH/pagina_inicial_RH.php
M	UI/RH/relatorios.php
A	assets/CSS/Colaborador/pagina_inicial_colaborador.css
M	assets/CSS/Comuns/login.css
M	assets/CSS/Comuns/notificacoes.css
M	assets/CSS/RH/dashboard_rh.css
M	assets/CSS/RH/equipa_nova.css
M	assets/CSS/RH/equipas.css
M	assets/CSS/RH/exportar.css
A	assets/CSS/RH/pagina_inicial.css
M	assets/CSS/RH/relatorios.css
A	assets/fundo.png

commit 5c47ceccf503e9252186c7d469828743d5c6ecbf	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Thu Jun 26 17:23:57 2025 +0100

    Ficha Colaborador
    
    Ficha Colaborador concluida, base de dados atualizada.

M	UI/Colaborador/ficha_colaborador.php
M	assets/CSS/Colaborador/ficha_colaborador.css

commit 691b56cddb4835b362aec2d17fcda7f4d7bbc2a3	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Thu Jun 26 16:03:06 2025 +0100

    css

M	UI/Admin/alertas.php
M	UI/Admin/campos_personalizados.php
M	UI/Admin/dashboard_admin.php
M	UI/Admin/pagina_inicial_admin.php
M	UI/Admin/permissoes.php
M	UI/Admin/utilizador_editar.php
M	UI/Admin/utilizadores.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Comuns/login.php
M	UI/Comuns/perfil.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/equipas.php
M	UI/RH/relatorios.php
M	assets/CSS/Comuns/perfil.css
M	assets/CSS/RH/colaboradores_gerir.css

commit 2fc8d8f4de77d30203d0b097dbba807866194a40	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Thu Jun 26 15:42:08 2025 +0100

    atualizações css

M	UI/Admin/alertas.php
M	UI/Admin/campos_personalizados.php
M	UI/Admin/pagina_inicial_admin.php
M	UI/Admin/permissoes.php
M	UI/Admin/utilizador_novo.php
M	UI/Admin/utilizadores.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipas.php

commit b5896e6b29ac96566dc0e50349c3c84bb0c0e60c	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Thu Jun 26 15:15:38 2025 +0100

    atualizações css
    
    A deixar o header igual em todas as files

M	UI/Admin/alertas.php
M	UI/Admin/campos_personalizados.php
M	UI/Admin/dashboard_admin.php
A	UI/Admin/pagina_inicial_admin.php
M	UI/Admin/permissoes.php
M	UI/Admin/utilizadores.php
M	UI/Colaborador/dashboard_colaborador.php
M	UI/Comuns/erro.php
M	UI/Comuns/perfil.php
M	UI/Convidado/dashboard_convidado.php
M	UI/Convidado/onboarding_convidado.php
M	UI/Coordenador/dashboard_coordenador.php
M	UI/Coordenador/equipa.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
M	UI/RH/relatorios.php
M	assets/CSS/Admin/base.css
M	assets/CSS/Colaborador/dashboard_colaborador.css
M	assets/CSS/Comuns/notificacoes.css
M	assets/CSS/Comuns/perfil.css
M	assets/CSS/RH/colaboradores_gerir.css
M	assets/CSS/RH/equipas.css
M	assets/CSS/RH/relatorios.css

commit 5dee055fdef28229ad1c12fb1ef107c7ce73dc90	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Thu Jun 26 12:22:42 2025 +0100

    css Admin
    
    atribuicao e alterações da estilização do Admin

M	UI/Admin/alertas.php
M	UI/Admin/campos_personalizados.php
M	UI/Admin/dashboard_admin.php
M	UI/Admin/permissoes.php
M	UI/Admin/utilizadores.php
M	UI/Colaborador/dashboard_colaborador.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Convidado/dashboard_convidado.php
M	UI/Convidado/onboarding_convidado.php
M	UI/Coordenador/dashboard_coordenador.php
M	UI/Coordenador/equipa.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
M	UI/RH/relatorios.php
A	assets/CSS/Admin/alertas.css
A	assets/CSS/Admin/base.css
A	assets/CSS/Admin/campos.css
A	assets/CSS/Admin/dashboard.css
A	assets/CSS/Admin/utilizadores.css

commit 068567b80e79792e1ba7e52df0b9865fe3838f92	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Thu Jun 26 11:27:42 2025 +0100

    Estilos da pagina de login atualizados
    
    Cores alteradas para uma identidade mais similar à empresa

M	UI/Comuns/login.php
M	assets/CSS/Comuns/login.css
A	assets/tlantic-logo-escuro.png

commit 03135a30fbb8796bc82abce20dc0c23e5023f163	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Thu Jun 26 11:02:08 2025 +0100

    atualização para a mesma metodologia que Patrick (trabalho conjunto)

M	BLL/Admin/BLL_alertas.php
M	BLL/Admin/BLL_campos_personalizados.php
M	BLL/Admin/BLL_dashboard_admin.php
M	BLL/Admin/BLL_permissoes.php
M	BLL/Admin/BLL_utilizadores.php
M	BLL/Colaborador/BLL_dashboard_colaborador.php
M	BLL/Colaborador/BLL_ficha_colaborador.php
M	BLL/Comuns/BLL_notificacoes.php
M	BLL/Comuns/BLL_perfil.php
M	BLL/Convidado/BLL_dashboard_convidado.php
M	BLL/Convidado/BLL_onboarding_convidado.php
M	BLL/Coordenador/BLL_dashboard_coordenador.php
M	BLL/RH/BLL_colaboradores_gerir.php
M	BLL/RH/BLL_dashboard_rh.php
M	BLL/RH/BLL_equipas.php
M	BLL/RH/BLL_exportar.php
M	BLL/RH/BLL_relatorios.php
M	DAL/Admin/DAL_alertas.php
M	DAL/Admin/DAL_campos_personalizados.php
M	DAL/Admin/DAL_dashboard_admin.php
M	DAL/Admin/DAL_permissoes.php
M	DAL/Admin/DAL_utilizadores.php
D	DAL/Colaborador/BLL_ficha_colaborador.php
M	DAL/Colaborador/DAL_dashboard_colaborador.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
M	DAL/Comuns/DAL_login.php
M	DAL/Comuns/DAL_notificacoes.php
M	DAL/Comuns/DAL_perfil.php
M	DAL/Convidado/DAL_dashboard_convidado.php
M	DAL/Convidado/DAL_onboarding_convidado.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
M	DAL/RH/DAL_colaboradores_gerir.php
M	DAL/RH/DAL_dashboard_rh.php
M	DAL/RH/DAL_equipas.php
M	DAL/RH/DAL_exportar.php
M	DAL/RH/DAL_relatorios.php
M	UI/Admin/utilizador_editar.php
M	UI/Admin/utilizador_novo.php
M	UI/Admin/utilizador_remover.php
M	UI/Colaborador/dashboard_colaborador.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Comuns/erro.php
M	UI/Comuns/login.php
M	UI/Comuns/logout.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Coordenador/dashboard_coordenador.php
M	UI/Coordenador/equipa.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
M	UI/RH/relatorios.php
A	assets/CSS/Colaborador/dashboard_colaborador.css
A	assets/CSS/Colaborador/ficha_colaborador.css
R100	DAL/Colaborador/BLL_dashboard_colaborador.php	assets/CSS/Comuns/erro.css
A	assets/CSS/Comuns/login.css
A	assets/CSS/Comuns/logout.css
A	assets/CSS/Comuns/notificacoes.css
A	assets/CSS/Comuns/perfil.css
A	assets/CSS/Coordenador/dashboard_coordenador.css
A	assets/CSS/Coordenador/equipa.css
A	assets/CSS/Coordenador/relatorios_equipa.css
A	assets/CSS/RH/colaborador_novo.css
A	assets/CSS/RH/colaboradores_gerir.css
A	assets/CSS/RH/dashboard_rh.css
A	assets/CSS/RH/equipa_nova.css
A	assets/CSS/RH/equipas.css
A	assets/CSS/RH/exportar.css
A	assets/CSS/RH/relatorios.css
D	assets/style.css
D	assets/styles.css
D	assets/teste.css
A	assets/tlantic-logo.png

commit dfe9fb67b734f390963751d35b785e35330eadc6	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Thu Jun 26 10:54:05 2025 +0100

    css change

M	assets/styles.css

commit 79b2b174fa8cb0ed0b460fee557acb766b76b3b5	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Wed Jun 25 18:19:13 2025 +0100

    atualizações

M	UI/Colaborador/ficha_colaborador.php
M	assets/style.css
D	assets/tlantic-logo.png
A	assets/tlantic-logo2.png

commit 2651d0265d5ec118aec710bf3845d89b51a02847	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Wed Jun 25 09:08:10 2025 +0100

    Atualização campos ficha colaborador

M	DAL/RH/DAL_colaboradores_gerir.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php

commit e8c398b6416ff2fc06f99b5c73c15010ab6115d6	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Tue Jun 24 23:33:23 2025 +0100

    Atualizei o código da ficha do colaborador
    
    Adicionados atributos extra e a disposição/permissões das atualizações da ficha do colaborador

M	DAL/Colaborador/BLL_ficha_colaborador.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
M	UI/Colaborador/ficha_colaborador.php

commit 73affbad2e93437c231cb3bdc4118a1653bdca81	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Tue Jun 24 16:53:36 2025 +0100

    Update index.php

M	index.php

commit eb0c8a03bd1c2f188f1f1e0d2a78056e9c957012	refs/remotes/origin/miguel_branch
Author: Miguel Correia <1231245@isep.ipp.pt>
Date:   Tue Jun 24 16:51:49 2025 +0100

    Update index.php

M	index.php
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
commit fcb9f629c59c29fe642d2a7a468e3455248dad67	refs/remotes/origin/bruno_branch (origin/bruno_branch)
Author: Bruno Costa <1231247@isep.ipp.pt>
Date:   Fri Jun 27 12:03:02 2025 +0100

    pós relatorios

M	BLL/Admin/BLL_alertas.php
M	BLL/Admin/BLL_campos_personalizados.php
M	BLL/Admin/BLL_dashboard_admin.php
M	BLL/Admin/BLL_permissoes.php
M	BLL/Admin/BLL_utilizadores.php
M	BLL/Authenticator.php
M	BLL/Colaborador/BLL_dashboard_colaborador.php
M	BLL/Colaborador/BLL_ficha_colaborador.php
M	BLL/Comuns/BLL_notificacoes.php
M	BLL/Comuns/BLL_perfil.php
M	BLL/Convidado/BLL_dashboard_convidado.php
M	BLL/Convidado/BLL_onboarding_convidado.php
M	BLL/Coordenador/BLL_dashboard_coordenador.php
M	BLL/RH/BLL_colaboradores_gerir.php
M	BLL/RH/BLL_dashboard_rh.php
M	BLL/RH/BLL_equipas.php
M	BLL/RH/BLL_exportar.php
M	BLL/RH/BLL_relatorios.php
M	DAL/Admin/DAL_alertas.php
M	DAL/Admin/DAL_campos_personalizados.php
M	DAL/Admin/DAL_dashboard_admin.php
M	DAL/Admin/DAL_permissoes.php
M	DAL/Admin/DAL_utilizadores.php
M	DAL/Colaborador/DAL_dashboard_colaborador.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
M	DAL/Comuns/DAL_login.php
M	DAL/Comuns/DAL_notificacoes.php
M	DAL/Comuns/DAL_perfil.php
M	DAL/Convidado/DAL_dashboard_convidado.php
M	DAL/Convidado/DAL_onboarding_convidado.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
M	DAL/RH/DAL_colaboradores_gerir.php
M	DAL/RH/DAL_dashboard_rh.php
M	DAL/RH/DAL_equipas.php
M	DAL/RH/DAL_exportar.php
M	DAL/RH/DAL_relatorios.php
M	UI/Admin/alertas.php
M	UI/Admin/campos_personalizados.php
M	UI/Admin/dashboard_admin.php
A	UI/Admin/pagina_inicial_admin.php
M	UI/Admin/permissoes.php
M	UI/Admin/utilizador_editar.php
M	UI/Admin/utilizador_novo.php
M	UI/Admin/utilizador_remover.php
M	UI/Admin/utilizadores.php
M	UI/Colaborador/dashboard_colaborador.php
M	UI/Colaborador/ficha_colaborador.php
A	UI/Colaborador/pagina_inicial_colaborador.php
M	UI/Comuns/erro.php
M	UI/Comuns/login.php
M	UI/Comuns/logout.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Convidado/dashboard_convidado.php
M	UI/Convidado/onboarding_convidado.php
M	UI/Coordenador/dashboard_coordenador.php
M	UI/Coordenador/equipa.php
M	UI/Coordenador/relatorios_equipa.php
M	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
A	UI/RH/pagina_inicial_RH.php
M	UI/RH/relatorios.php
A	assets/1.png
A	assets/2.png
A	assets/3.png
A	assets/4.png
A	assets/5.png
A	assets/6.png
A	assets/CSS/Admin/alertas.css
A	assets/CSS/Admin/base.css
A	assets/CSS/Admin/campos.css
A	assets/CSS/Admin/dashboard.css
A	assets/CSS/Admin/utilizadores.css
A	assets/CSS/Colaborador/dashboard_colaborador.css
A	assets/CSS/Colaborador/ficha_colaborador.css
A	assets/CSS/Colaborador/pagina_inicial_colaborador.css
R100	DAL/Colaborador/BLL_dashboard_colaborador.php	assets/CSS/Comuns/erro.css
A	assets/CSS/Comuns/login.css
R100	DAL/Colaborador/BLL_ficha_colaborador.php	assets/CSS/Comuns/logout.css
A	assets/CSS/Comuns/notificacoes.css
A	assets/CSS/Comuns/perfil.css
A	assets/CSS/Coordenador/dashboard_coordenador.css
A	assets/CSS/Coordenador/equipa.css
A	assets/CSS/Coordenador/relatorios_equipa.css
A	assets/CSS/RH/colaborador_novo.css
A	assets/CSS/RH/colaboradores_gerir.css
A	assets/CSS/RH/dashboard_rh.css
A	assets/CSS/RH/equipa_nova.css
A	assets/CSS/RH/equipas.css
A	assets/CSS/RH/exportar.css
A	assets/CSS/RH/pagina_inicial.css
A	assets/CSS/RH/relatorios.css
A	assets/fundo.png
D	assets/style.css
D	assets/styles.css
D	assets/teste.css
A	assets/tlantic-logo-escuro.png
A	assets/tlantic-logo2.png
M	index.php
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

