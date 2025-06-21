# Commits by author
#### 1230654@isep.ipp.pt
<details>
<summary>Diff</summary>

<pre>
 BLL/Authenticator.php   |    2 !!
 b/BLL/Authenticator.php |    1 !
 2 files changed, 3 modifications(!)
</pre>
</details>
<details>
<summary>Commits</summary>

<pre>
commit ce63660e33d5e4fe87d33c4e859f81d5a28f63c8	refs/heads/main
Author: 1230654@isep.ipp.pt <1230654@isep.ipp.pt>
Date:   Wed Jun 18 11:20:08 2025 +0100

    Update Authenticator.php

M	BLL/Authenticator.php

commit 48551415eaeeffe97e9edf5f074784ee715368d3	refs/heads/main
Author: 1230654@isep.ipp.pt <1230654@isep.ipp.pt>
Date:   Wed Jun 18 11:18:30 2025 +0100

    Update Authenticator.php

M	BLL/Authenticator.php

commit cc4affbb91823a7055a91de04923ea0521e42d29	refs/heads/main
Author: 1230654@isep.ipp.pt <1230654@isep.ipp.pt>
Date:   Wed Jun 18 11:11:27 2025 +0100

    Update Authenticator.php

M	BLL/Authenticator.php
</pre>

</details>

#### 1230881@isep.ipp.pt
<details>
<summary>Diff</summary>

<pre>
 .gitattributes                                  |    2 
 /BLL/Authenticator.php                          |  188 +++++++++++++++
 /DAL/Colaborador/DAL_ficha_colaborador.php      |   12 
 /DAL/Database.php                               |   23 +
 /DAL/UserDataAcess.php                          |    7 
 /UI/Admin/alertas.html                          |   59 ++++
 /UI/Admin/campos_personalizados.html            |   56 ++++
 /UI/Admin/dashboard_admin.html                  |   50 ++++
 /UI/Admin/permissoes.html                       |   59 ++++
 /UI/Admin/utilizador_editar.php                 |   93 +++++++
 /UI/Admin/utilizadores.html                     |   65 +++++
 /UI/Colaborador/dashboard_colaborador.html      |   35 ++
 /UI/Colaborador/ficha_colaborador.html          |   37 +++
 /UI/Comuns/erro.html                            |   15 +
 /UI/Comuns/login.php                            |  184 +++++++++++++++
 /UI/Comuns/notificacoes.html                    |   28 ++
 /UI/Comuns/perfil.html                          |   30 ++
 /UI/Convidado/dashboard_convidado.html          |   21 +
 /UI/Convidado/onboarding_convidado.html         |   27 ++
 /UI/Coordenador/dashboard_coordenador.html      |   42 +++
 /UI/RH/colaboradores_gerir.html                 |   63 +++++
 /UI/RH/dashboard_rh.html                        |   48 +++
 /UI/RH/equipas.html                             |   57 ++++
 /UI/RH/exportar.html                            |   36 ++
 /UI/RH/relatorios.html                          |   44 +++
 BLL/Admin/BLL_permissoes.php                    |   13 +
 BLL/Admin/BLL_utilizadores.php                  |   13 +
 BLL/Colaborador/BLL_ficha_colaborador.php       |   21 +
 BLL/Comuns/BLL_login.php                        |  202 ++++++++++++++++
 BLL/Comuns/BLL_notificacoes.php                 |   13 +
 BLL/Comuns/BLL_perfil.php                       |   13 +
 BLL/Coordenador/BLL_dashboard_coordenador.php   |   13 +
 BLL/RH/BLL_colaboradores_gerir.php              |   13 +
 BLL/RH/BLL_equipas.php                          |   13 +
 DAL/Admin/DAL_permissoes.php                    |   15 +
 DAL/Admin/DAL_utilizadores.php                  |   15 +
 DAL/Colaborador/DAL_ficha_colaborador.php       |   30 ++
 DAL/Comuns/DAL_login.php                        |   26 ++
 DAL/Comuns/DAL_notificacoes.php                 |   12 
 DAL/Comuns/DAL_perfil.php                       |   12 
 DAL/Coordenador/DAL_dashboard_coordenador.php   |   13 +
 DAL/RH/DAL_colaboradores_gerir.php              |   16 +
 DAL/RH/DAL_equipas.php                          |   15 +
 README.md                                       |    2 
 UI/Admin/alertas.html                           |   28 ++
 UI/Admin/alertas.php                            |   25 -
 UI/Admin/campos_personalizados.html             |   28 ++
 UI/Admin/campos_personalizados.php              |   25 -
 UI/Admin/dashboard_admin.html                   |   28 ++
 UI/Admin/dashboard_admin.php                    |   11 
 UI/Admin/permissoes.html                        |   28 ++
 UI/Admin/permissoes.php                         |   75 +++-
 UI/Admin/utilizadores.html                      |   28 ++
 UI/Admin/utilizadores.php                       |   33 -
 UI/Colaborador/dashboard_colaborador.html       |   28 ++
 UI/Colaborador/dashboard_colaborador.php        |   11 
 UI/Colaborador/ficha_colaborador.html           |   28 ++
 UI/Colaborador/ficha_colaborador.php            |  179 ++++++++++++!!
 UI/Comuns/erro.html                             |   28 ++
 UI/Comuns/login.php                             |    5 
 UI/Comuns/notificacoes.html                     |   28 ++
 UI/Comuns/notificacoes.php                      |   17 +
 UI/Comuns/perfil.html                           |   28 ++
 UI/Comuns/perfil.php                            |   13 !
 UI/Convidado/dashboard_convidado.html           |   28 ++
 UI/Convidado/dashboard_convidado.php            |   12 
 UI/Convidado/onboarding_convidado.html          |   28 ++
 UI/Convidado/onboarding_convidado.php           |   14 !
 UI/Coordenador/dashboard_coordenador.html       |   28 ++
 UI/Coordenador/dashboard_coordenador.php        |   11 
 UI/RH/colaboradores_gerir.html                  |   28 ++
 UI/RH/colaboradores_gerir.php                   |   27 -
 UI/RH/dashboard_rh.html                         |   28 ++
 UI/RH/dashboard_rh.php                          |   11 
 UI/RH/equipas.html                              |   28 ++
 UI/RH/equipas.php                               |   45 +-
 UI/RH/exportar.html                             |   28 ++
 UI/RH/exportar.php                              |    7 
 UI/RH/relatorios.html                           |   28 ++
 UI/RH/relatorios.php                            |   19 +
 a/BLL/Authenticator.php                         |  190 ---------------
 a/DAL/UserDataAcess.php                         |    7 
 b/BLL/Admin/BLL_alertas.php                     |   13 +
 b/BLL/Admin/BLL_campos_personalizados.php       |   13 +
 b/BLL/Admin/BLL_dashboard_admin.php             |   13 +
 b/BLL/Admin/BLL_permissoes.php                  |    3 
 b/BLL/Admin/BLL_utilizadores.php                |   18 +
 b/BLL/Authenticator.php                         |  208 +++++++++++++++++
 b/BLL/Colaborador/BLL_dashboard_colaborador.php |   13 +
 b/BLL/Colaborador/BLL_ficha_colaborador.php     |    3 
 b/BLL/Comuns/BLL_login.php                      |  124 --------!
 b/BLL/Comuns/BLL_notificacoes.php               |    3 
 b/BLL/Comuns/BLL_perfil.php                     |    8 
 b/BLL/Convidado/BLL_dashboard_convidado.php     |   13 +
 b/BLL/Convidado/BLL_onboarding_convidado.php    |   13 +
 b/BLL/Coordenador/BLL_dashboard_coordenador.php |    6 
 b/BLL/RH/BLL_colaboradores_gerir.php            |    5 
 b/BLL/RH/BLL_dashboard_rh.php                   |   13 +
 b/BLL/RH/BLL_equipas.php                        |    6 
 b/BLL/RH/BLL_exportar.php                       |   13 +
 b/BLL/RH/BLL_relatorios.php                     |   13 +
 b/DAL/Admin/DAL_alertas.php                     |   11 
 b/DAL/Admin/DAL_campos_personalizados.php       |   11 
 b/DAL/Admin/DAL_dashboard_admin.php             |   13 +
 b/DAL/Admin/DAL_permissoes.php                  |   10 
 b/DAL/Admin/DAL_utilizadores.php                |   54 ++++
 b/DAL/Colaborador/DAL_dashboard_colaborador.php |   13 +
 b/DAL/Colaborador/DAL_ficha_colaborador.php     |   29 !!
 b/DAL/Comuns/DAL_login.php                      |   19 +
 b/DAL/Comuns/DAL_notificacoes.php               |    6 
 b/DAL/Comuns/DAL_perfil.php                     |   10 
 b/DAL/Convidado/DAL_dashboard_convidado.php     |   13 +
 b/DAL/Convidado/DAL_onboarding_convidado.php    |   12 
 b/DAL/Coordenador/DAL_dashboard_coordenador.php |   19 +
 b/DAL/Database.php                              |    1 
 b/DAL/RH/DAL_colaboradores_gerir.php            |   45 +++
 b/DAL/RH/DAL_dashboard_rh.php                   |   13 +
 b/DAL/RH/DAL_equipas.php                        |   19 +
 b/DAL/RH/DAL_exportar.php                       |   11 
 b/DAL/RH/DAL_relatorios.php                     |   19 +
 b/DAL/UserDataAcess.php                         |   13 +
 b/UI/Admin/alertas.html                         |    1 
 b/UI/Admin/alertas.php                          |    5 
 b/UI/Admin/campos_personalizados.html           |    1 
 b/UI/Admin/campos_personalizados.php            |   91 ++++++-
 b/UI/Admin/dashboard_admin.html                 |    1 
 b/UI/Admin/dashboard_admin.php                  |    5 
 b/UI/Admin/permissoes.html                      |    1 
 b/UI/Admin/permissoes.php                       |   88 ++++++!
 b/UI/Admin/utilizador_editar.php                |  107 +++++++!
 b/UI/Admin/utilizador_novo.php                  |   85 ++++++
 b/UI/Admin/utilizador_remover.php               |   16 +
 b/UI/Admin/utilizadores.html                    |    1 
 b/UI/Admin/utilizadores.php                     |   99 +++++++
 b/UI/Colaborador/dashboard_colaborador.html     |    1 
 b/UI/Colaborador/dashboard_colaborador.php      |    4 
 b/UI/Colaborador/ficha_colaborador.html         |    1 
 b/UI/Colaborador/ficha_colaborador.php          |   34 ++
 b/UI/Comuns/erro.html                           |    1 
 b/UI/Comuns/erro.php                            |    3 
 b/UI/Comuns/login.php                           |   92 ++++++!
 b/UI/Comuns/logout.php                          |    6 
 b/UI/Comuns/notificacoes.html                   |    1 
 b/UI/Comuns/notificacoes.php                    |   50 +++!
 b/UI/Comuns/perfil.html                         |    1 
 b/UI/Comuns/perfil.php                          |   88 ++++++!
 b/UI/Convidado/dashboard_convidado.html         |    1 
 b/UI/Convidado/dashboard_convidado.php          |    1 
 b/UI/Convidado/onboarding_convidado.html        |    1 
 b/UI/Convidado/onboarding_convidado.php         |    1 
 b/UI/Coordenador/dashboard_coordenador.html     |    1 
 b/UI/Coordenador/dashboard_coordenador.php      |   20 !
 b/UI/Coordenador/equipa.php                     |   61 ++++
 b/UI/Coordenador/relatorios_equipa.php          |   42 +++
 b/UI/RH/colaborador_novo.php                    |  167 +++++++++++++
 b/UI/RH/colaboradores_gerir.html                |    1 
 b/UI/RH/colaboradores_gerir.php                 |   77 +++!!!
 b/UI/RH/dashboard_rh.html                       |    1 
 b/UI/RH/dashboard_rh.php                        |   22 +
 b/UI/RH/equipa_nova.php                         |   79 ++++++
 b/UI/RH/equipas.html                            |    1 
 b/UI/RH/equipas.php                             |   91 ++++++-
 b/UI/RH/exportar.html                           |    1 
 b/UI/RH/exportar.php                            |   38 ++!
 b/UI/RH/relatorios.html                         |    1 
 b/UI/RH/relatorios.php                          |   22 +
 b/assets/chatbot.js                             |   10 
 b/assets/script.js                              |   40 +++
 b/assets/style.css                              |  293 ++++++++++++++++++++++++
 b/assets/styles.css                             |  168 +++++++++++++
 b/assets/teste.css                              |  104 ++++++++
 b/assets/tlantic-logo.png                       |binary
 b/destroy_session.php                           |    5 
 b/index.php                                     |    3 
 destroy_session.php                             |    5 
 175 files changed, 5152 insertions(+), 360 deletions(-), 362 modifications(!)
</pre>
</details>
<details>
<summary>Commits</summary>

<pre>
commit c2cf3f5e3ca42c92c7d8d6171b9ca58411556aa6	refs/heads/main (HEAD -> main, origin/main, origin/HEAD)
Author: Patrick Costa <1230881@isep.ipp.pt>
Date:   Fri Jun 20 09:02:34 2025 +0100

    20/06

M	BLL/Colaborador/BLL_ficha_colaborador.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
M	UI/Admin/campos_personalizados.php
M	UI/Admin/permissoes.php
M	UI/Admin/utilizador_editar.php
M	UI/Admin/utilizadores.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/RH/equipas.php
D	destroy_session.php

commit ac6bdb22d5556078f1676409300a76b96113b41d	refs/heads/main
Author: PatrickCosta1 <1230881@isep.ipp.pt>
Date:   Wed Jun 18 19:16:59 2025 +0100

    Atualizado(funcoes do rh em curso)

M	BLL/Admin/BLL_permissoes.php
M	BLL/Admin/BLL_utilizadores.php
M	BLL/Colaborador/BLL_ficha_colaborador.php
M	BLL/Comuns/BLL_login.php
M	BLL/Comuns/BLL_notificacoes.php
M	BLL/Comuns/BLL_perfil.php
M	BLL/Coordenador/BLL_dashboard_coordenador.php
M	BLL/RH/BLL_colaboradores_gerir.php
M	BLL/RH/BLL_equipas.php
M	DAL/Admin/DAL_permissoes.php
M	DAL/Admin/DAL_utilizadores.php
M	DAL/Colaborador/DAL_ficha_colaborador.php
M	DAL/Comuns/DAL_login.php
M	DAL/Comuns/DAL_notificacoes.php
M	DAL/Comuns/DAL_perfil.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
M	DAL/Database.php
M	DAL/RH/DAL_colaboradores_gerir.php
M	DAL/RH/DAL_equipas.php
M	UI/Admin/alertas.php
M	UI/Admin/campos_personalizados.php
M	UI/Admin/dashboard_admin.php
M	UI/Admin/permissoes.php
A	UI/Admin/utilizador_editar.php
A	UI/Admin/utilizador_novo.php
A	UI/Admin/utilizador_remover.php
M	UI/Admin/utilizadores.php
M	UI/Colaborador/dashboard_colaborador.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Comuns/login.php
A	UI/Comuns/logout.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Convidado/dashboard_convidado.php
M	UI/Convidado/onboarding_convidado.php
M	UI/Coordenador/dashboard_coordenador.php
A	UI/Coordenador/equipa.php
A	UI/Coordenador/relatorios_equipa.php
A	UI/RH/colaborador_novo.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
A	UI/RH/equipa_nova.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
M	UI/RH/relatorios.php
A	assets/style.css
A	destroy_session.php

commit 8fa486586cb2b239a8d62cd26e0d4686fa31e2f4	refs/heads/main
Author: PatrickCosta1 <1230881@isep.ipp.pt>
Date:   Wed Jun 18 16:05:04 2025 +0100

    gf

M	BLL/Admin/BLL_alertas.php
M	BLL/Admin/BLL_campos_personalizados.php
M	BLL/Admin/BLL_dashboard_admin.php
M	BLL/Admin/BLL_permissoes.php
M	BLL/Admin/BLL_utilizadores.php
A	BLL/Authenticator.php
M	BLL/Colaborador/BLL_dashboard_colaborador.php
M	BLL/Colaborador/BLL_ficha_colaborador.php
M	BLL/Comuns/BLL_login.php
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
A	DAL/Colaborador/DAL_dashboard_colaborador.php
A	DAL/Colaborador/DAL_ficha_colaborador.php
M	DAL/Comuns/DAL_login.php
M	DAL/Comuns/DAL_notificacoes.php
M	DAL/Comuns/DAL_perfil.php
M	DAL/Convidado/DAL_dashboard_convidado.php
M	DAL/Convidado/DAL_onboarding_convidado.php
M	DAL/Coordenador/DAL_dashboard_coordenador.php
A	DAL/Database.php
M	DAL/RH/DAL_colaboradores_gerir.php
M	DAL/RH/DAL_dashboard_rh.php
M	DAL/RH/DAL_equipas.php
M	DAL/RH/DAL_exportar.php
M	DAL/RH/DAL_relatorios.php
A	DAL/UserDataAcess.php
M	UI/Admin/alertas.php
M	UI/Admin/campos_personalizados.php
M	UI/Admin/dashboard_admin.php
M	UI/Admin/permissoes.php
M	UI/Admin/utilizadores.php
M	UI/Colaborador/dashboard_colaborador.php
M	UI/Colaborador/ficha_colaborador.php
M	UI/Comuns/erro.php
M	UI/Comuns/login.php
M	UI/Comuns/notificacoes.php
M	UI/Comuns/perfil.php
M	UI/Convidado/dashboard_convidado.php
M	UI/Convidado/onboarding_convidado.php
M	UI/Coordenador/dashboard_coordenador.php
M	UI/RH/colaboradores_gerir.php
M	UI/RH/dashboard_rh.php
M	UI/RH/equipas.php
M	UI/RH/exportar.php
M	UI/RH/relatorios.php

commit 736aefd80c7f0cea60ed46f29f51d0e72f43889f	refs/heads/main
Author: PatrickCosta1 <1230881@isep.ipp.pt>
Date:   Wed Jun 18 13:16:27 2025 +0100

    files

R100	DAL/ff	BLL/Admin/BLL_alertas.php
A	BLL/Admin/BLL_campos_personalizados.php
A	BLL/Admin/BLL_dashboard_admin.php
A	BLL/Admin/BLL_permissoes.php
A	BLL/Admin/BLL_utilizadores.php
A	BLL/Colaborador/BLL_dashboard_colaborador.php
A	BLL/Colaborador/BLL_ficha_colaborador.php
A	BLL/Comuns/BLL_erro.php
M	BLL/Comuns/BLL_login.php
A	BLL/Comuns/BLL_notificacoes.php
A	BLL/Comuns/BLL_perfil.php
A	BLL/Convidado/BLL_dashboard_convidado.php
A	BLL/Convidado/BLL_onboarding_convidado.php
A	BLL/Coordenador/BLL_dashboard_coordenador.php
A	BLL/RH/BLL_colaboradores_gerir.php
A	BLL/RH/BLL_dashboard_rh.php
A	BLL/RH/BLL_equipas.php
A	BLL/RH/BLL_exportar.php
A	BLL/RH/BLL_relatorios.php
A	DAL/Admin/DAL_alertas.php
A	DAL/Admin/DAL_campos_personalizados.php
A	DAL/Admin/DAL_dashboard_admin.php
A	DAL/Admin/DAL_permissoes.php
A	DAL/Admin/DAL_utilizadores.php
A	DAL/Colaborador/BLL_dashboard_colaborador.php
A	DAL/Colaborador/BLL_ficha_colaborador.php
A	DAL/Comuns/DAL_erro.php
A	DAL/Comuns/DAL_notificacoes.php
A	DAL/Comuns/DAL_perfil.php
A	DAL/Convidado/DAL_dashboard_convidado.php
A	DAL/Convidado/DAL_onboarding_convidado.php
A	DAL/Coordenador/DAL_dashboard_coordenador.php
A	DAL/RH/DAL_colaboradores_gerir.php
A	DAL/RH/DAL_dashboard_rh.php
A	DAL/RH/DAL_equipas.php
A	DAL/RH/DAL_exportar.php
A	DAL/RH/DAL_relatorios.php
R100	UI/Admin/alertas.html	UI/Admin/alertas.php
R100	UI/Admin/campos_personalizados.html	UI/Admin/campos_personalizados.php
R100	UI/Admin/dashboard_admin.html	UI/Admin/dashboard_admin.php
R100	UI/Admin/permissoes.html	UI/Admin/permissoes.php
R100	UI/Admin/utilizadores.html	UI/Admin/utilizadores.php
R100	UI/Colaborador/dashboard_colaborador.html	UI/Colaborador/dashboard_colaborador.php
R100	UI/Colaborador/ficha_colaborador.html	UI/Colaborador/ficha_colaborador.php
R100	UI/Comuns/erro.html	UI/Comuns/erro.php
R100	UI/Comuns/notificacoes.html	UI/Comuns/notificacoes.php
R100	UI/Comuns/perfil.html	UI/Comuns/perfil.php
R100	UI/Convidado/dashboard_convidado.html	UI/Convidado/dashboard_convidado.php
R100	UI/Convidado/onboarding_convidado.html	UI/Convidado/onboarding_convidado.php
R100	UI/Coordenador/dashboard_coordenador.html	UI/Coordenador/dashboard_coordenador.php
R100	UI/RH/colaboradores_gerir.html	UI/RH/colaboradores_gerir.php
R100	UI/RH/dashboard_rh.html	UI/RH/dashboard_rh.php
R100	UI/RH/equipas.html	UI/RH/equipas.php
R100	UI/RH/exportar.html	UI/RH/exportar.php
R100	UI/RH/relatorios.html	UI/RH/relatorios.php

commit ece6983b5120b245a266354e91575ddc99851614	refs/heads/main
Author: PatrickCosta1 <1230881@isep.ipp.pt>
Date:   Wed Jun 18 13:01:54 2025 +0100

    at

D	BLL/Authenticator.php
A	BLL/Comuns/BLL_login.php
A	DAL/Comuns/DAL_login.php
D	DAL/UserDataAcess.php
A	DAL/ff
M	UI/Comuns/login.php

commit c7323d708f8450b43da3067e53ffc788a9a32456	refs/heads/main
Author: PatrickCosta1 <1230881@isep.ipp.pt>
Date:   Wed Jun 18 11:53:16 2025 +0100

    Chat

M	UI/Admin/alertas.html
M	UI/Admin/campos_personalizados.html
M	UI/Admin/dashboard_admin.html
M	UI/Admin/permissoes.html
M	UI/Admin/utilizadores.html
M	UI/Colaborador/dashboard_colaborador.html
M	UI/Colaborador/ficha_colaborador.html
M	UI/Comuns/erro.html
M	UI/Comuns/login.php
M	UI/Comuns/notificacoes.html
M	UI/Comuns/perfil.html
M	UI/Convidado/dashboard_convidado.html
M	UI/Convidado/onboarding_convidado.html
M	UI/Coordenador/dashboard_coordenador.html
M	UI/RH/colaboradores_gerir.html
M	UI/RH/dashboard_rh.html
M	UI/RH/equipas.html
M	UI/RH/exportar.html
M	UI/RH/relatorios.html

commit e982770357844ec29d8a483fa3e25948d9ab7b4f	refs/heads/main
Author: PatrickCosta1 <1230881@isep.ipp.pt>
Date:   Wed Jun 18 11:43:07 2025 +0100

    Chat

M	UI/Admin/alertas.html
M	UI/Admin/campos_personalizados.html
M	UI/Admin/dashboard_admin.html
M	UI/Admin/permissoes.html
M	UI/Admin/utilizadores.html
M	UI/Colaborador/dashboard_colaborador.html
M	UI/Colaborador/ficha_colaborador.html
M	UI/Comuns/erro.html
M	UI/Comuns/notificacoes.html
M	UI/Comuns/perfil.html
M	UI/Convidado/dashboard_convidado.html
M	UI/Convidado/onboarding_convidado.html
M	UI/Coordenador/dashboard_coordenador.html
M	UI/RH/colaboradores_gerir.html
M	UI/RH/dashboard_rh.html
M	UI/RH/equipas.html
M	UI/RH/exportar.html
M	UI/RH/relatorios.html

commit 08029dca8c321002c4607c30fddce9f111bd4faa	refs/heads/main
Author: PatrickCosta1 <1230881@isep.ipp.pt>
Date:   Wed Jun 18 10:57:46 2025 +0100

    Atualização 18/06
    
    18/06

D	.gitattributes
A	BLL/Authenticator.php
A	DAL/UserDataAcess.php
D	README.md
A	UI/Admin/alertas.html
A	UI/Admin/campos_personalizados.html
A	UI/Admin/dashboard_admin.html
A	UI/Admin/permissoes.html
A	UI/Admin/utilizadores.html
A	UI/Colaborador/dashboard_colaborador.html
A	UI/Colaborador/ficha_colaborador.html
A	UI/Comuns/erro.html
A	UI/Comuns/login.php
A	UI/Comuns/notificacoes.html
A	UI/Comuns/perfil.html
A	UI/Convidado/dashboard_convidado.html
A	UI/Convidado/onboarding_convidado.html
A	UI/Coordenador/dashboard_coordenador.html
A	UI/RH/colaboradores_gerir.html
A	UI/RH/dashboard_rh.html
A	UI/RH/equipas.html
A	UI/RH/exportar.html
A	UI/RH/relatorios.html
A	assets/chatbot.js
A	assets/script.js
A	assets/styles.css
A	assets/teste.css
A	assets/tlantic-logo.png
A	index.php
</pre>

</details>

#### 1231247@isep.ipp.pt
<details>
<summary>Diff</summary>

<pre>
 Authenticator.php |    2 ++
 1 file changed, 2 insertions(+)
</pre>
</details>
<details>
<summary>Commits</summary>

<pre>
commit 5a390b2e26787c05d12ea98f4224200f409fb10c	refs/heads/main
Author: Bruno-costaui <1231247@isep.ipp.pt>
Date:   Wed Jun 18 11:07:09 2025 +0100

    Update Authenticator.php

M	BLL/Authenticator.php
</pre>

</details>

#### miguelscorreia24@gmail.com
<details>
<summary>Diff</summary>

<pre>
 UserDataAcess.php |    1 !
 1 file changed, 1 modification(!)
</pre>
</details>
<details>
<summary>Commits</summary>

<pre>
commit 021f47342be5bbe79313e0bc21ef8e6bf1a5245b	refs/heads/main
Author: Miguel Correia <miguelscorreia24@gmail.com>
Date:   Wed Jun 18 11:44:58 2025 +0100

    Update UserDataAcess.php

M	DAL/UserDataAcess.php
</pre>

</details>

