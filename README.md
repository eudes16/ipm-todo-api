# IPM Todo API

Este é um projeto de uma API simples desenvolvido em PHP que lista tarefas.

## Funcionalidades

- Lista tarefas
- Adicionar tarefas
- Marcar tarefas como concluídas
- Remover tarefas

## Requisitos

- PHP 8.0 ou superior
- Servidor web (Apache, Nginx, etc.)
- Banco de dados MySQL

## Instalação

1. Crie o banco de dados:
    ```sql
    CREATE DATABASE `ipm_todo` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
    ```
2. Crie a tabela:
    ```sql
    -- Definição da tabela todos
    CREATE TABLE `todos` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `description` text,
    `status` enum('open','close') NOT NULL DEFAULT 'open',
    `due_date` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
    ```

3. Clone o repositório:
    ```sh
    git clone https://github.com/seu-usuario/ipm-todo-api.git
    ```
4. Navegue até o diretório do projeto:
    ```sh
    cd ipm-todo-api
    ```
5. Crie e configure o arquivo `.env`.
    ```sh
    ## Na raiz do projeto
    touch .env
    ```
    ```
    APP_NAME=IPM Todo API
    APP_VERSION=1.0.0
    APP_MODE=development
    APP_URL=http://localhost:8080

    DATABASE_HOST=127.0.0.1
    DATABASE_PORT=3306
    DATABASE_NAME=ipm_todo
    DATABASE_USER=root
    DATABASE_PASSWORD=root

    # 0 = none, 1 = sql, 2 = sql + values
    DATABASE_LOG_LEVEL=2
    ```

6. Inicie o serviço.
    ```sh
    php -S localhost:8080 -t public
    ```

## Uso

1. GET - /
    ```sh
    curl --request GET \
    --url http://localhost:8081/
    ```
2. GET - /todos
    ```sh
    curl --request GET \
    --url http://localhost:8081/todos 
    ```

    ```sh
    # Paginando
    curl --request GET \
    --url 'http://localhost:8081/todos?page=1&limit=1' 
    ```
    Obs: é adicinado ao retorno os dados da paginação
    ```json
    {
        "data": [
            {
                "id": 1,
                "title": "Lorem Ipsum",
                "description": "Lorem Ipsum....",
                "status": "close",
                "due_date": "2024-10-06 11:17:31",
                "created_at": "2024-10-04 11:17:48",
                "updated_at": "2024-10-06 16:33:47"
            }
        ],
        "code": 200,
        "message": "",
        "pagination": {
            "page": 1,
            "limit": 1,
            "total": 3,
            "pages": 3,
            "next": 2,
            "prev": null
        }
    }
    ```
    ```sh
    # Ordenação simples
    curl --request GET \
    --url 'http://localhost:8081/todos?order=id' 

    # Ordenação simples descendente
    curl --request GET \
    --url 'http://localhost:8081/todos?order=id_desc' 

    # Ordenação composta
    curl --request GET \
    --url 'http://localhost:8081/todos?order=id_desc,title' 
    ```
    
    ```sh
    # Filtrando
    # id=1 ou id_eq=1
    curl --request GET \
    --url 'http://localhost:8081/todos?id=1'

    # id_in=1,3,4
    curl --request GET \
    --url 'http://localhost:8081/todos?id_in=1%2C2%2C4'

    # title_like=Lorem Ipsum
    curl --request GET \
    --url 'http://localhost:8081/todos?title_like=Lorem%20Ipsum' \
    --header 'User-Agent: insomnia/10.0.0'
    ```
3. POST - /todos

    ```sh
    # due_date é não obrigatório
    curl --request POST \
    --url http://localhost:8081/todos \
    --header 'Content-Type: application/json' \
    --data '{
        "title": "Lorem Ipsum",
        "description": "Lorem Ipsum...",
        "due_date": "2024-10-06 14:00:00"
    }'
    ```
    ```json
    // Response HTTP-201
    {
        "data": {
            "id": 19,
            "title": "Lorem Ipsum",
            "description": "Lorem Ipsum...",
            "status": "open",
            "due_date": "2024-10-06 14:00:00",
            "created_at": "2024-10-05 01:39:59",
            "updated_at": null
        },
        "code": 201,
        "message": ""
    }
    ```
4. PUT - /todos/:id
    ```sh
    curl --request PUT \
    --url http://localhost:8081/todos/1 \
    --header 'Content-Type: application/json'
    --data '{
        "title": "Lorem Ipsum2",
        "description": "Lorem Ipsum2...",
        "due_date": "2024-10-06 17:00:00"
    }'
    ```
    ```json
    // Response HTTP-200
    {
        "data": {
            "id": 1,
            "title": "Lorem Ipsum2",
            "description": "Lorem Ipsum2...",
            "status": "open",
            "due_date": "2024-10-06 17:00:00",
            "created_at": "2024-10-05 01:39:59",
            "updated_at": null
        },
        "code": 200,
        "message": ""
    }
    ```
5. DELETE - /todos/:id
    ```sh
    curl --request DELETE \
    --url http://localhost:8081/todos/18
    ```
    ```json
    // Response HTTP-200
    {
        "data": [
            [
                {
                    "id": 18,
                    "title": "Lorem Ipsum",
                    "description": "Lorem Ipsum",
                    "status": "open",
                    "due_date": "2024-10-06 14:00:00",
                    "created_at": "2024-10-05 01:14:32",
                    "updated_at": null
                }
            ],
            1
        ],
        "code": 200,
        "message": ""
    }
    ```