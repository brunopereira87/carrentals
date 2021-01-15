Format: A1

# Instalação do Projeto

Você pode clonar este repositório OU baixar o .zip

Ao descompactar, é necessário rodar o **composer** pra instalar as dependências e gerar o _autoload_.

Vá até a pasta do projeto, pelo _prompt/terminal_ e execute:

> composer install

Depois é só aguardar.

# Banco de Dados

O código para a criação das tabelas do banco de dados em MySql, está disponível no arquivo 'carrentals.sql'

No arquivo Config.php, você pode trocar o host, o usuario do banco de dados e a senha, para fazer a conexão
com o banco de dados

# Execução

Você precisará de um servidor local com suporte a PHP 7.4 ou superior, para executar o projeto.

URL Base: [url-servidor-local]:[PORT(Default=80)]/carrentals/api

Ex: Se a url do seu servidor local é 'http::/localhost' e a sua porta 80 está ocupada por outra aplicação,
e você quer utilizar a porta 3000, a sua URL Base seria 'http::/localhost:3000/carrentals/api.

# API REST

Segue abaixo, a documentação básica da API:

## Auth

### REGISTRAR USUÁRIO

Registra um usuário caso ele não exista

**URL** : `/auth/register`

**Método** : `POST`

**Autenticação** : Não

**Exemplo do Body** Todos os campos são obrigatórios

```json
{
  "name": "Bruno Pereira",
  "email": "bruno@email.com",
  "password": "qwe123"
}
```

## Resposta de Sucesso

**Condição** : Email não estar cadastrado e todos os dados corretos.

**Exemplo**

```json
{
  "error": "",
  "data": {
    "id": 2,
    "name": "Bruno Pereira",
    "email": "bruno@email.com"
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MTA3MjQ5NjMsImRhdGEiOnsiaWQiOiIyIiwibmFtZSI6IkJydW5vIFBlcmVpcmEiLCJlbWFpbCI6ImJydW5vQGVtYWlsLmNvbSJ9fQ.BdLQ8-xMnwej3pvH6H6Pwrn9PUmJw8IqT-FrdBnzIjs"
}
```

### Erros

**Condição** : Usuário já existe.

```json
{
  "error": "Este email já está cadastrado"
}
```

### Ou

**Condição** : Campos obrigatórios faltando.

```json
{
  "error": "Nome, email e senha são campos obrigatórios"
}
```

### LOGIN

Autentica um usuário, logando-o no sistema

**URL** : `/auth/login`

**Método** : `POST`

**Autenticação** : Não

**Exemplo do Body** Todos os campos são obrigatórios

```json
{
  "email": "bruno@email.com",
  "password": "qwe123"
}
```

## Resposta de Sucesso

**Condição** : Usuário cadastrado e todos os dados corretos.

**Exemplo**

```json
{
  "error": "",
  "data": {
    "id": 2,
    "name": "Bruno Pereira",
    "email": "bruno@email.com"
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MTA3MjQ5NjMsImRhdGEiOnsiaWQiOiIyIiwibmFtZSI6IkJydW5vIFBlcmVpcmEiLCJlbWFpbCI6ImJydW5vQGVtYWlsLmNvbSJ9fQ.BdLQ8-xMnwej3pvH6H6Pwrn9PUmJw8IqT-FrdBnzIjs"
}
```

### Erros

**Condição** : Usuário não existe ou senha incorreta.

```json
{
  "error": "Email e/ou senha inválido"
}
```

### Ou

**Condição** : Campos obrigatórios faltando.

```json
{
  "error": "Email e senha são campos obrigatórios"
}
```

### LOGGED

Retorna os do usuário logado

**URL** : `/auth/logged`

**Método** : `GET`

**Autenticação** : Sim

**Headers** :
Authentication: Bearer Token

## Resposta de Sucesso

**Condição** : Usuário cadastrado e todos os dados corretos.

**Exemplo**

```json
{
  "data": {
    "id": "2",
    "name": "Bruno Pereira",
    "email": "bruno@email.com",
    "role": "1"
  }
}
```

### Erros

**Condição** : Token inválido

```json
{
  "error": "Token inválido"
}
```

## Cars

### REGISTRAR CARRO

Registra um carro

**URL** : `/cars`

**Método** : `POST`

**Autenticação** : Sim

**Headers**
Authentication: Bearer Token

**Exemplo do Body** Os campos name e rental_price são obrigatórios

```json
{
  "name": "Uno 2005",
  "plate": "MJ45K-AM8",
  "company": "Fiat",
  "rental_price": 100
}
```

## Resposta de Sucesso

**Condições**  
-Usuário autenticado e autorizado.
-Todos os campos obrigatórios preenchidos.

**Exemplo**

```json
{
  "data": {
    "name": "Uno 2005",
    "plate": "MJ45K-AM8",
    "company": "Fiat",
    "rental_price": 100,
    "id": "5"
  }
}
```

### Erros

**Condição** : Token inválido

```json
{
  "error": "Token inválido"
}
```

### Ou

**Condição** : Usuário não autorizado

```json
{
  "error": "Você não tem permissão para acessar essa área"
}
```

### Ou

**Condição** : Campos obrigatórios faltando.

```json
{
  "error": "Os campos name e rental_price são obrigatório e devem ser preenchidos corretamente"
}
```

### LISTAR TODOS OS CARROS

Lista todos os carros cadastrados

**URL** : `/cars`

**Método** : `GET`

**Autenticação** : Sim

**Headers**
Authentication: Bearer Token

## Resposta de Sucesso

**Condições**  
-Usuário autenticado.

**Exemplo**

```json
{
  "data": [
    {
      "name": "Fox 2015",
      "plate": "BC102-AM8",
      "company": "Volkswagen",
      "rental_price": 100,
      "rented": 0,
      "rental_user": null
    },
    {
      "name": "Fox",
      "plate": "GA102-AM8",
      "company": "Volkswagen",
      "rental_price": 100,
      "rented": 1,
      "rental_user": {
        "id": "4",
        "name": "Marina Pereira",
        "email": "marina@email.com"
      }
    },
    {
      "name": "Uno 2005",
      "plate": "MJ45K-AM8",
      "company": "Fiat",
      "rental_price": 100,
      "rented": 0,
      "rental_user": null
    }
  ]
}
```

### Erros

**Condição** : Token inválido

```json
{
  "error": "Token inválido"
}
```

**URL** : `/auth/login`

**Método** : `POST`

**Autenticação** : Não

**Exemplo do Body** Todos os campos são obrigatórios

```json
{
  "email": "bruno@email.com",
  "password": "qwe123"
}
```

## Resposta de Sucesso

**Condição** : Usuário cadastrado e todos os dados corretos.

**Exemplo**

```json
{
  "error": "",
  "data": {
    "id": 2,
    "name": "Bruno Pereira",
    "email": "bruno@email.com"
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MTA3MjQ5NjMsImRhdGEiOnsiaWQiOiIyIiwibmFtZSI6IkJydW5vIFBlcmVpcmEiLCJlbWFpbCI6ImJydW5vQGVtYWlsLmNvbSJ9fQ.BdLQ8-xMnwej3pvH6H6Pwrn9PUmJw8IqT-FrdBnzIjs"
}
```

### Erros

**Condição** : Usuário não existe ou senha incorreta.

```json
{
  "error": "Email e/ou senha inválido"
}
```

### Ou

**Condição** : Campos obrigatórios faltando.

```json
{
  "error": "Email e senha são campos obrigatórios"
}
```

### LOGGED

Retorna os do usuário logado

**URL** : `/auth/logged`

**Método** : `GET`

**Autenticação** : Sim

**Headers** :
Authentication: Bearer Token

## Resposta de Sucesso

**Condição** : Usuário cadastrado e todos os dados corretos.

**Exemplo**

```json
{
  "data": {
    "id": "2",
    "name": "Bruno Pereira",
    "email": "bruno@email.com",
    "role": "1"
  }
}
```

### Erros

**Condição** : Token inválido

```json
{
  "error": "Token inválido"
}
```

## Cars

### REGISTRAR CARRO

Registra um carro

**URL** : `/cars`

**Método** : `POST`

**Autenticação** : Sim

**Headers**
Authentication: Bearer Token

**Exemplo do Body** Os campos name e rental_price são obrigatórios

```json
{
  "name": "Uno 2005",
  "plate": "MJ45K-AM8",
  "company": "Fiat",
  "rental_price": 100
}
```

## Resposta de Sucesso

**Condições**  
-Usuário autenticado e autorizado.
-Todos os campos obrigatórios preenchidos.

**Exemplo**

```json
{
  "data": {
    "name": "Uno 2005",
    "plate": "MJ45K-AM8",
    "company": "Fiat",
    "rental_price": 100,
    "id": "5"
  }
}
```

### Erros

**Condição** : Token inválido

```json
{
  "error": "Token inválido"
}
```

### Ou

**Condição** : Usuário não autorizado

```json
{
  "error": "Você não tem permissão para acessar essa área"
}
```

### Ou

**Condição** : Campos obrigatórios faltando.

```json
{
  "error": "Os campos name e rental_price são obrigatório e devem ser preenchidos corretamente"
}
```

### LISTAR TODOS OS CARROS

Lista todos os carros cadastrados

**URL** : `/cars`

**Método** : `GET`

**Autenticação** : Sim

**Headers**
Authentication: Bearer Token

## Resposta de Sucesso

**Condições**  
-Usuário autenticado.

**Exemplo**

```json
{
  "data": [
    {
      "id": "1",
      "name": "Fox 2015",
      "plate": "BC102-AM8",
      "company": "Volkswagen",
      "rental_price": 100,
      "rented": 0,
      "rental_user": null
    },
    {
      "id": "4",
      "name": "Fox",
      "plate": "GA102-AM8",
      "company": "Volkswagen",
      "rental_price": 100,
      "rented": 1,
      "rental_user": {
        "id": "4",
        "name": "Marina Pereira",
        "email": "marina@email.com"
      }
    },
    {
      "id": "5",
      "name": "Uno 2005",
      "plate": "MJ45K-AM8",
      "company": "Fiat",
      "rental_price": 100,
      "rented": 0,
      "rental_user": null
    }
  ]
}
```

### Erros

**Condição** : Token inválido

```json
{
  "error": "Token inválido"
}
```

**Ou**

**Condição** : Token inválido

```json
{
  "error": "Token inválido"
}
```

### RETORNA UM CARRO

Retorna um carro à partir do id

**URL** : `/cars/:id`

**Método** : `GET`

**Autenticação** : Sim

**Headers**
Authentication: Bearer Token

## Resposta de Sucesso

**Condições**  
-Usuário autenticado.
-Carro cadastrado
**Exemplo**

```json
{
  "data": [
    {
      "id": "1",
      "name": "Fox 2015",
      "plate": "BC102-AM8",
      "company": "Volkswagen",
      "rental_price": 100,
      "rented": 0,
      "rental_user": null
    }
  ]
}
```

### Erros

**Condição** : Token inválido

```json
{
  "error": "Token inválido"
}
```

**Ou**

**Condição** : ID não encontrado

```json
{
  "error": "Carro não encontrado"
}
```

### ATUALIZA OS DADOS DE UM CARRO

Atualiza os dados de um carro à partir do id, caso seja encontado

**URL** : `/cars/:id`

**Método** : `PUT`

**Autenticação** : Sim

**Headers**
Authentication: Bearer Token

**Exemplo do Body**

```json
{
  "name": "Fox 2015",
  "plate": "BC102-AM8"
}
```

## Resposta de Sucesso

**Condições**  
-Usuário autenticado.
-Usuário autorizado
-Carro cadastrado

**Exemplo**

```json
{
  "data": [
    {
      "id": "1",
      "name": "Fox 2015",
      "plate": "BC102-AM8",
      "company": "Volkswagen",
      "rental_price": 100,
      "rented": 0,
      "rental_user": null
    }
  ]
}
```

### Erros

**Condição** : Token inválido

```json
{
  "error": "Token inválido"
}
```

**Ou**

```json
{
  "error": "Você não tem permissão para acessar essa área"
}
```

**Condição** : ID não encontrado

```json
{
  "error": "Carro não encontrado"
}
```

### DELETA UM CARRO

Deleta um carro à partir do id

**URL** : `/cars/:id`

**Método** : `DELETE`

**Autenticação** : Sim

**Headers**
Authentication: Bearer Token

## Resposta de Sucesso

**Condições**  
-Usuário autenticado.
-Usuário autorizado
-Carro cadastrado
**Exemplo**

```json
[]
```

### Erros

**Condição** : Token inválido

```json
{
  "error": "Token inválido"
}
```

**Ou**

**Condição** : Usuário não autorizado

```json
{
  "error": "Você não tem permissão para acessar essa área"
}
```

### ALUGA UM CARRO

Atualiza o campo rent de um carro à partir do id, sinalizando que o carro foi alugado. Insere o id do usuário que alugou

**URL** : `/cars/:id/rent`

**Método** : `PUT`

**Autenticação** : Sim

**Headers**
Authentication: Bearer Token

**Exemplo do Body** Todos os campos são obrigatórios

```json
{
  "rental_user_id": 2
}
```

## Resposta de Sucesso

**Condições**  
-Usuário autenticado.
-Usuário autorizado
-Usuário que está alugando o carro cadastrado
-Carro cadastrado
-Carro não alugado

**Exemplo**

```json
{
  "message": "Carro alugado com sucesso"
}
```

### Erros

**Condição** : Token inválido

```json
{
  "error": "Token inválido"
}
```

**Ou**

**Condição** : Usuário não autorizado

```json
{
  "error": "Você não tem permissão para acessar essa área"
}
```

**Ou**

**Condição** : ID não encontrado

```json
{
  "error": "Carro não encontrado"
}
```

**Ou**

**Condição** : Carro já alugado

```json
{
  "error": "Este caro já está alugado"
}
```

**Condição** : Usuário não encontrado

```json
{
  "error": "Usuário não encontrado"
}
```

### DEVOLVER UM CARRO

Atualiza o campo rent de um carro à partir do id, sinalizando que o carro foi devolvido. Remove o id do usuário

**URL** : `/cars/:id/return`

**Método** : `PUT`

**Autenticação** : Sim

**Headers**
Authentication: Bearer Token

## Resposta de Sucesso

**Condições**  
-Usuário autenticado.
-Usuário autorizado
-Carro cadastrado
-Carro alugado

**Exemplo**

```json
{
  "message": "Carro devolvido com sucesso"
}
```

### Erros

**Condição** : Token inválido

```json
{
  "error": "Token inválido"
}
```

**Ou**

**Condição** : Usuário não autorizado

```json
{
  "error": "Você não tem permissão para acessar essa área"
}
```

**Ou**

**Condição** : ID não encontrado

```json
{
  "error": "Carro não encontrado"
}
```

**Ou**

**Condição** : Carro não alugado

```json
{
  "error": "Este caro não está alugado"
}
```
