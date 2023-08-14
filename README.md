# Cotação de Fretes

Este projeto tem como objetivo o teste de conhecimento de criação, documentação, testes e manutenção de um projeto de software, assim como a aplicação de conceitos de princípios e padrões de arquitetura de software.

## Requisitos

O projeto atenderá a necessidade de realizar cotações de frete para volumes associados a uma empresa, utilizando o serviço externo Frete Rápido. Bem como poderão ser consultados resultados de métricas das últimas cotações realizadas.

O projeto deve garantir que os dados sejam validados previamente ao envio para o serviço externo e que respeitem as especificações da API da Frete Rápido.

### Requisitos Técnicos
 - O serviço deve ser acessado atrávés de uma API REST
 - Utilizar Boas práticas de programação
 - Aplicar TDD

### Tecnologias
 - PHP >=8.2
 - Laravel 10.x
 - Docker e Docker Compose
 - Devcontainers p/ ambiente de desenvolvimento
 - PHP Unit p/ testes automatizados

### API

- [POST] localhost:80/api/quote
> Permite que seja feita uma cotação de fretes seguindo o seguinte formato de mensagem:
```bash
curl --request POST \
  --url http://localhost/api/quote \
  --header 'Accept:  application/json' \
  --header 'content-type:  application/json' \
  --data '{
   "recipient":{
      "address":{
         "zipcode":"01311-000"
      }
   },
   "volumes":[
      {
         "category":7,
         "amount":1,
         "unitary_weight":5,
         "price":5,
         "sku":"abc-teste-123",
         "height":0.2,
         "width":0.2,
         "length":0.2
      },
      {
         "category":7,
         "amount":2,
         "unitary_weight":4,
         "price":556,
         "sku":"abc-teste-527",
         "height":0.4,
         "width":0.6,
         "length":0.15
      }
   ]
}
'
```

 O retorno esperado atenderá os seguintes formatos

 > Em caso de sucesso:
```json
HTTP: 200
{
  "carrier":[
     {
        "name":"EXPRESSO FR",
        "service":"Rodoviário",
        "deadline":"3",
        "price":17
     },
     {
        "name":"Correios",
        "service":"SEDEX",
        "deadline":1,
        "price":20.99
     }
  ]
}
```

- [GET] localhost:80/api/metrics?last_quotes={?}

```bash
curl --request GET \
  --url http://localhost/api/metrics \
  --header 'Accept:  application/json'
```

 O retorno esperado atenderá os seguintes formatos
 
 > Em caso de sucesso:
```json
{
  "quotes_metrics": {
    "carriers_metrics": [
      {
        "name": "BTU BRASPRESS",
        "offers_quantity": 3,
        "total_price": 296.05,
        "average_price": 98.68
      },
      {
        "name": "CORREIOS",
        "offers_quantity": 4,
        "total_price": 310.12,
        "average_price": 77.53
      },
      {
        "name": "UBER",
        "offers_quantity": 4,
        "total_price": 240.96,
        "average_price": 60.24
      }
    ],
    "lower_price": 55.74,
    "higher_price": 103.35
  }
}
```

Para ambos endpoints, em caso de erros ou mensagens de validação sera retornada uma mensagem como a seguinte:

```json
HTTP: 500 ou 400
{
    "message": "Mensagem de erro",
    "errors": [<somente em caso de erros de validação>]
}
```

## instalação e Utilização

Para executar localmente este projeto clone este repositório:
```bash
git clone https://github.com/mihailov-vf/freight-test
cd freight-test
```

Ajuste as variáveis de ambiente do projeto, copiando o arquivo `.env.example` e adicionando os dados de acesso na API FreteRápido:
```bash
cp .env.example .env
```

```shell
CREDENCIAIS_CNPJ="<INSERIR_DADOS>"
CREDENCIAIS_TOKEN="<INSERIR_DADOS>"
CREDENCIAIS_CODIGO_PLATAFORMA="<INSERIR_DADOS>"
ENVIO_CEP="<INSERIR_DADOS>" # CEP do remetente
```

Execute o comando para instalação do projeto:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

Certifique-se de que o docker está instalado em seu computador e execute:
```bash
docker-compose up -d
```

Antes de iniciar a utilização, garanta que o banco de dados esteja com os dados iniciais necessários:
```bash
docker-compose exec quotes php artisan migrate --seed
```

Para acessar o container e realizar outras operações pode-se executar:
```bash
docker-compose exec quotes bash
```

### Testes Automatizados
Para executar os testes, acesse o conteirner através de um terminal e execute:
```bash
composer tests # para os principais testes (Mais rápido)
# e
composer tests:complete # para incluir os testes de integração e aceitação (Mais lento)
```
