## Movies API - Clean Architecture + Laravel

Se trata de um projeto de construção de uma API de CRUD de filmes, utilizando conceitos de Clean Architecture. Diversos recursos padrão do Laravel, como injeção de dependências ou uso de Facades e Models diretamente, não foram amplamente aplicados por esse motivo.

Na raiz do projeto se encontra uma pasta chamada docs com uma collection do Insomnia contendo todas as rotas.

Obs: Rotas de update foram implementadas utilizando POST por esperarem request com multipart/form-data, causando um problema devido a uma limitação do PHP: [PATCH and PUT Request Does not Working with form-data](https://stackoverflow.com/questions/50691938/patch-and-put-request-does-not-working-with-form-data)

#### Regras de négocio:
- Ao criar uma conta, o usuário receberá um token no email para confirmar seu email e, só então, obeterá acesso aos filmes marcados como "privados";
- CPF, foto de perfil e capa de filme são campos opcionais em todas as rotas;
- Caso um usuário está com email não validado, receberá 404 ao tentar acessar um filme privado;
- Caso um usuário está com email não validado, receberá 403 ao tentar acessar uma capa de um filme que existe mas é privado;
- Caso um usuário está com email não validado, a rota de listagem de filmes listará apenas filmes públicos. Caso contrário, listará todos (também sendo possível filtrar por visibilidade);
- Caso um usuário troque de email, seu email volta a ser "não confirmado" e receberá um novo token de validação;

#### Usuários predefinidos (tanto localmente quando em produção)
- **Administrador**: {
    email: "admin@mail.com", "password": "password"
}

- **Usuário com email verificado**: {
    email: "verified@mail.com", "password": "password"
}

- **Usuário com email não verificado**: {
    email: "unverified@mail.com", "password": "password"
}

#### Instruções para rodar localmente (necessário ter o composer e docker instalados)
- Clone o projeto
- Entre na pasta do projeto clonado e rode o seguinte comando para instalar o Sail
```
composer require laravel/sail --dev
```
- Rode o seguinte comando para subir os containers docker
```
sail up -d
```
- Copie o arquivo .env.example para um arquivo .env
- Rode o seguinte comando para gerar uma app key
```
sail artisan key:generate --ansi
```
- Altere as varíaveis MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSOWORD, MAIL_ENCRYPTION de acordo com seu serviço de email
- Variáveis de ambiente como JWT_SECRET e credenciais de banco já foram providas por simplicidade, mas podem ser alteradas caso desejado
- E, por fim, rode o seguinte comando para rodar migrations e popular o banco
```
sail artisan migrate --seed
```
#### Testes unitários
- Comando para rodar testes unitários
```
sail test
```
