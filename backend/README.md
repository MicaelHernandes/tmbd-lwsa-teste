# Backend - API de Filmes

Este projeto é uma API RESTful desenvolvida em Laravel para cadastro, autenticação e gerenciamento de filmes favoritos dos usuários.

## Requisitos

- PHP >= 8.1
- Composer
- SQLite (ou outro banco de dados configurado)
- Docker (opcional)

## Instalação

1. Clone o repositório:
   ```bash
   git clone <repo-url>
   cd backend
   ```
2. Instale as dependências:
   ```bash
   composer install
   ```
3. Copie o arquivo de ambiente e configure:
   ```bash
   cp .env.example .env
   # Edite o .env conforme necessário (DB_CONNECTION, etc)
   ```
4. Gere a chave da aplicação:
   ```bash
   ./vendor/bin/sail artisan key:generate
   ```
5. Execute as migrations:
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

## Execução

Com o Laravel Sail:

```bash
./vendor/bin/sail up -d
```
A API estará disponível em `http://localhost` (ou porta configurada no docker-compose).

## Autenticação

A autenticação é feita via Sanctum (token). Para acessar rotas protegidas, registre-se e faça login para obter o token.

## Rotas

### Auth
- `POST /api/auth/register`  
  Cadastro de usuário  
  **Body:** `name`, `email`, `password`, `password_confirmation`
- `POST /api/auth/login`  
  Login do usuário  
  **Body:** `email`, `password`

### Filmes
- `GET /api/movies`  
  Lista todos os filmes
- `GET /api/movies/search?query=nome`  
  Busca filmes por nome

### Favoritos (Requer autenticação)
- `GET /api/favorite_movies`  
  Lista filmes favoritos do usuário autenticado
- `POST /api/favorite_movies`  
  Adiciona um filme aos favoritos  
  **Body:** `movie_id` (ID do filme)
- `DELETE /api/favorite_movies`  
  Remove um filme dos favoritos  
  **Body:** `movie_id` (ID do filme)

## Validações

- Cadastro: nome, email único, senha mínima de 8 caracteres e confirmação.
- Login: email e senha obrigatórios.
- Favoritos: `movie_id` obrigatório e válido.

## Testes

Execute os testes dentro do Sail:
```bash
./vendor/bin/sail artisan test
```

## Estrutura

- `app/Http/Controllers`: Controllers da API
- `app/Models`: Modelos Eloquent
- `app/Services`: Lógica de negócio (serviços)
- `database/migrations`: Migrations do banco
- `routes/api.php`: Definição das rotas da API

## Observações

- Este backend deve ser executado dentro da pasta `backend` do projeto.
- Utilize o token de autenticação retornado no login para acessar rotas protegidas, enviando no header:  
  `Authorization: Bearer {token}`
- A busca de filmes pode estar integrada a uma API externa (ex: TheMovieDB).
