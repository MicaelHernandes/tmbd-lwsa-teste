# TMDB LWSA Teste

Este projeto é uma aplicação fullstack que utiliza a API do The Movie Database (TMDB) para exibir e gerenciar filmes favoritos de usuários.

## Pré-requisitos
- Docker e Docker Compose instalados
- Conta gratuita no [The Movie Database (TMDB)](https://www.themoviedb.org/)

## Como obter sua chave TMDB
1. Siga o tutorial oficial para criar sua conta e obter a chave de API: [Tutorial Educative.io](https://www.educative.io/courses/movie-database-api-python/set-up-the-credentials)
2. Após obter sua chave, siga os passos abaixo para configurar o projeto.

## Configuração do Backend
1. Copie o arquivo de variáveis de ambiente:
   ```sh
   cp backend/.env.example backend/.env
   ```
2. Edite o arquivo `backend/.env` e insira sua chave TMDB no campo:
   ```env
   TMDB_TOKEN=sua_chave_tmdb_aqui
   ```

## Subindo a aplicação
Na raiz do projeto, execute:
```sh
docker compose up -d
```
Isso irá subir os containers do backend e frontend.

## Acessando a aplicação
- O frontend estará disponível em: [http://localhost:80](http://localhost:5173)
- O backend estará disponível em: [http://localhost:8000](http://localhost:8000)

## Repositório
[https://github.com/MicaelHernandes/tmbd-lwsa-teste](https://github.com/MicaelHernandes/tmbd-lwsa-teste)

---
Em caso de dúvidas, consulte a documentação dos diretórios `backend/README.md` e `frontend/README.md` ou abra uma issue no repositório.

