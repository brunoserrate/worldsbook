### Adicionar todos os arquivos alterados
```sh
git add .
```

### Gravar mudanças no repositório
Grava as alterações adicionadas e adiciona uma mensagem
```sh
git commit -m "Mensagem"
```

### Puxar atualizações do repositório remoto para o repositório local
O comando serve para puxar as atualizações do repositório, no caso como o mesmo está remoto na plataforma github, o comando irá buscar as atualizações do repositório remoto para o repisitório local
```sh
git pull origin <branch>
```
Mudar "< branch >" pela branch que está trabalhando. Exemplo:
```sh
git pull origin main
```

### Enviar atualizações do repositório remoto para o repositório local
O comando serve para enviar as atualizações do repositório, no caso como o mesmo está remoto na plataforma github, o comando irá atualizar o repositório remoto  com as alterações do repisitório local
```sh
git push origin <branch>
```
Mudar "< branch >" pela branch que está trabalhando. Exemplo:
```sh
git push origin main
```