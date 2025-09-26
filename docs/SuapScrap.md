# Scap do Suap para Download da foto dos alunos

```
Para poder fazer o download da foto de peril de um aluno é preciso utilizar o Command "SuapDownloadProfilePic"
```

### 1) Uso do Command:

- Como aluno: Faz o download da própria foto


```bash
    php spark getpic [matricula] [senha]
```

- Com acesso ao perfil de outros alunos: baixa a imagem do aluno com a matrícula indicada

```bash
    php spark getpic [username] [senha] [matricula_do_aluno]
```

sendo [matricula_do_aluno] o número de matrícula do aluno do qual deseja fazer o download da imagem

### 2) Refs:

O Command **SuapDownloadProfilePic** atualmente é dependente destas bibliotecas de terceiros:

- symfony/dom-crawler  
- symfony/css-selector 
- fabpot/goutte 
- guzzlehttp/guzzle

### 3) Funcionamento:

- Para fazer o download da foto, primeiramente é preciso ter acesso à URL da imagem servido pelo próprio Suap. Para ter acesso à essa imagem (e em boa qualidade) é preciso entrar dentro da página daquele aluno específico dentro do suap, isso pode ser feito por meio do endereço: **https://suap.ifro.edu.br/edu/aluno/{matricula}**, onde "{matricula}" deve ser substituído pelo número de matrícula do aluno.

- O endereço da págian do aluno é protegido e requer autenticação e permissão especial. no Suap o aluno tem acesso à própria página, logo, fazendo login com o própio acesso é possível fazer o download da própria imagem. Este cenário é o padrão seguido pelo Command.

- Para o resgate da foto de perfil de usuários que não sejam o próprio aluno, é preciso ter acesso especial ao sistema. O Command então aceita um terceiro argumento, sendo este o número de matrícula do aluno que se deseja fazer o download da foto de peril.

- O Command realiza o login no SUAP simulando um navegador e:
1. Resgata o valor do **CSRF_TOKEN** para poder fazer o login;
2. Envia o formulário de login com os dados de acesso fornecidos (matricula e senha);
3. Acessa a própria página de perfil (ou a do aluno específicado);
4. Busca pelo elemento específico que contém a foto dentro do HTML da página acessada.
5. Armazena a URL da imagem;
6. Faz uma requisição para o endereço da imagem e armazena o resultado (um arquivo) dentro do diretório específicado pelo Command.