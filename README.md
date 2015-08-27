lliure wolf
-----------

> 7.0 (Perdigueiro Português)

#### 7.0 Wolf (22/08/2015)
- [bug] - reconhecimento url amiaveis
- [bug] - tratamento de onclient
- [update] - alterações no processamento de urls em thumbs.php
- [bug] - adição de definição de valor padrão em $llconf->execucao
- [update] - suporte para conversão automática de url para comum ou amigavel
- [update] - alterada forma de entrada para apps de desktop, não é apresentado o ?app na url
- [bug] - adicionado strtolower ao método ll_app::setNome para poder entrar valores em maiúsculo
- [update] - Api aplimo reformulado com flexbox
- [update] - .gitignore da rais adicionado
- [update] - instalada como opt o recurso font-awesome
- [update] - instalada fonte como fonte padrão Open Sans
- [update] - na configuração do aplicado no arquivo .ll aceita o grupo que pode acessar o app ex: <seguranca>admin</seguranca>
- [update] - função de conversão de array para objeto adicionado a biblioteca jf
- [update] - Adição das extenções de fontes no .htaccess
- [update] - Alteração na api midias para usar passando menos parametros
- [update] - opt user reformulada aos novos padrões
- [update] - entrada de GET opt adicionada ao sistema
- [update] - Alterado nomenclatura do modo sen_html para onclient, $_ll['sen_html'] foi mantida por compatibilidade
- [update] - Removido arquivo kun_html.php e acoes.php da raiz
- [update] - Atualização em rotinas para inicio de arquivo llconf.ll em utf-8
- [update] - Atualização na função lltoObject() para suporte a utf-8
- [bug] - atualização nos padrões de css class .column
- [bug] - aterando entrada na função jf_insert() de != para !== de 'NULL'
- [bug] - correção na api aplimo referente a montagem do menu superior na função hc_menu()
- [bug] - correção de htmlspecialchars na api Navigi 
- [bug] - ajustada codifição de caracteres nos arquivos kun_html.php e funcoes.js
- [update] - acrescentado os métodos onserver() e onclient() na api aplimo
- [bug] - mensagem de erro em não puxar o método header() na api aplimo
- [bug] - correções, erro ao acessar um banco não existente na api navigi
- [bug] - correções de acessos para arquivos
- [update] - adequação no navigi para receber as classe conforme a denominação das etiquetas
- [update] - criada a função lltoobject() para fazer o carregamento de arquivos ll
- [update] - acrescentada api tags
- [update] - header é requerido nos modos onserver e em sen_html
- [update] - desta versão em diante o lliure tera seu tema alterado conforme a versão corrente
- [update] - api appbar, titulo do app direciona para home do app quando logado como dev ou adm
- [update] - Arquivo thumbs.php alterado com função "Manual" e "Ajustado"
- [update] - updates de funcionamento no jfbox
- [update] - criação da api "mídias"

