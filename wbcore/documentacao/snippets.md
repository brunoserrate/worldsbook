### Rodar o wbcore
```sh
php artisan serve
```

### Limpar cache/config
Limpar o cache e o cache das configurações do .env
É importante utilizar o comando quando for mexer nas rotas da api/web ou trocar da base de dados
```sh
php artisan cache:clear
```
```sh
php artisan config:clear
```
Ou utilizar o comando "optimize" para rodar tudo de uma vez
```sh
php artisan optimize
```
### Resetar as filas (queues)
Quando trabalhamos com queues e alteramos alguma coisa na rotina que trabalha em background, é importante resetar as filas para puxar as novas atualizações
```sh
php artisan queue:restart
```