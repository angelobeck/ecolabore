# ecolabore

Ecolabore é um simples framework com backend em PHP e front em JS. 

A engine não possui funcionalidade específica, serve somente como base para outras aplicações que necessitem das funcionalidades do framework.

Dentre estas funcionalidades, podemos destacar:

* Um sistema poderoso de roteamento de aplicações, que torna a estrutura extremamente simples e flexível.
* Um renderizador inspirado nos "Lightning Web Components" da Salesforce,
guardando as devidas proporções - apenas as funcionalidades mais essenciais estão disponíveis.
 * Um sistema de empacotamento versátil, que inclui somente o conteúdo necessário para a sua aplicação.
* Camada de abstração para banco de dados.
* Camada de abstração para comunicação entre front e backend.
* Ligação "frouxa" entre componentes - permite que se aponte para componentes e módulos a serem desenvolvidos sem quebrar a aplicação.
* Geração de formulários e filtragem de dados a partir de arquivos de configuração - permite a criação de campos especiais de formulários e validação adequada.

Além disso, a engine conta com diversas camadas de proteção:

* Sanitização de dados ao entrar e sair do banco de dados.
* Validação de dados recebidos do front.
* Criptografia e hashing para proteção de dados pessoais, senhas e dados de sessão do usuário.
* Controle de acesso a arquivos
* "Empacotamento" do sistema para poucos arquivos, reduzindo a superfície de ataque.
* Suporte a  RewriteEngine que impede acesso direto a qualquer recurso do sistema.

