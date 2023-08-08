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

- [POST] /api/quote
> Permite que seja feita uma cotação de fretes seguindo o seguinte formato de consulta:
```
{
   "recipient":{
      "address":{
         "zipcode":"01311000"
      }
   },
   "volumes":[
      {
         "category":7,
         "amount":1,
         "unitary_weight":5,
         "price":349,
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
```

 O retorno esperado atenderá os seguintes formatos

 > Em caso de sucesso:
```
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

> Em caso de erros ou mensagens de validação:
```
HTTP: 500 ou 400
{
    "message": "Mensagem de erro",
}
```

- [GET] /api/metrics?last_quotes={?}
