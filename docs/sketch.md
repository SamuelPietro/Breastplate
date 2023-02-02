### Estrutura do projeto

+ "app": É a pasta principal para o aplicativo.
    + "Controllers": Armazena todos os controladores, que são responsáveis por lidar com as solicitações de usuários e gerenciar a interação com o modelo.
    + "Models": Armazena todos os modelos, que são responsáveis por gerenciar dados e realizar tarefas relacionadas ao banco de dados.
    + "Views": Armazena todas as visualizações, que são responsáveis por exibir os dados para o usuário.
        + "templates": Armazena os arquivos de layout da aplicação.
+ "docs": Armazena a documentação do projeto. Isso inclui manuais, guias de usuário, diagramas de arquitetura, notas de lançamento, entre outros tipos de documentação relacionados ao projeto.
+ "public": É a pasta pública, acessível ao usuário final. Armazena arquivos CSS, imagens e JavaScript que são acessíveis via navegador.
    + "index.php": É o ponto de entrada principal para o aplicativo.
+ "src": Contém o código fonte do aplicativo.
    + "Core": Contém o núcleo do aplicativo, que é responsável por gerenciar a lógica de negócios e outros componentes importantes.
        + "bootstrap.php": Contém código que inicializa e configura o aplicativo.
        + "Autoloader.php": Faz o autoloader de classes.
        + "helpers.php": Conter funções auxiliares úteis que podem ser usadas em toda a aplicação, como funções para tratar strings, formatar datas, etc.
        + "Router.php": Classe que implementa as funcionalidades de roteamento, como adicionar rotas, corresponder rotas a URLs específicas e redirecionar o usuário para o controlador adequado.
        + "routes.php": Este arquivo é responsável por definir as rotas da aplicação. Ele contém as chamadas para os métodos da classe Router que adicionam as rotas ao sistema de roteamento.
    + "Database": Contém código relacionado ao banco de dados, incluindo scripts de criação de tabelas e consultas SQL.
        + "Connection.php" : Faz a conexão com o banco de dados usando as informações do arquivo .env.
        + "Dao.php": É responsável por fornecer uma camada de abstração para acessar os dados armazenados no banco de dados. 
+ "tests": Armazena testes automatizados para o aplicativo.
+ "vendor": Armazena dependências externas instaladas com o Composer.
+ "composer.json": Contém informações sobre as dependências do aplicativo e outras configurações do Composer.
+ "composer.lock": Armazena informações sobre as versões exatas das dependências instaladas.
+ ".env": Armazena dados sensiveis e pessoais para execução do projeto



### Composer.json
"name": Especifica o nome do pacote, no formato "usuário/nome    +do    +pacote".
"description": Fornece uma descrição sucinta do projeto.
"type": Especifica o tipo de projeto, neste caso "project".
"license": Especifica a licença do projeto, neste caso "GPL    +3.0    +or    +later".
"autoload": Especifica as configurações de autoload do Composer para o projeto, usando o padrão PSR    +4.
"authors": Lista os autores do projeto, incluindo nome e e    +mail.
"minimum    +stability": Especifica a estabilidade mínima aceitável para dependências do projeto. Neste caso, é "dev".
"require": Lista as dependências do projeto, incluindo a versão mínima do PHP e as extensões PDO e JSON.
