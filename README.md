# Sistema de Pedidos

Sistema de pedidos com arquitetura em camadas, backend em PHP puro, frontend em HTML/CSS/JavaScript e persistência em JSON.

## Estrutura

- `backend/` - lógica de negócio, API e persistência
- `frontend/` - interface do usuário
- `Dockerfile` - containerização PHP Apache
- `docker-compose.yml` - orquestração para rodar localmente
- `.htaccess` - redireciona `/pedidos` para `backend/index.php`

## Como rodar localmente

1. No terminal, acesse a pasta do projeto:
   ```powershell
   cd c:\Users\FRANCISCO\Videos\pedidos
   ```
2. Inicie com Docker Compose:
   ```powershell
   docker-compose up -d
   ```
3. Abra no navegador:
   ```text
   http://localhost:8080
   ```

## Como rodar no GitHub Codespaces

1. Faça push do projeto para um repositório GitHub.
2. No GitHub, abra o repositório e clique em **Code → Codespaces → Create codespace on main**.
3. Aguarde o Codespace iniciar. Ele usará a configuração em `.devcontainer/devcontainer.json`.
4. No terminal do Codespace, verifique a porta aberta:
   ```bash
   curl http://localhost:8080
   ```
5. No GitHub Codespaces, abra a porta `8080` pelo painel **Ports**.
6. Clique em **Open in Browser** para acessar a aplicação.

> O `devcontainer` usa o `Dockerfile` do projeto para rodar o Apache/PHP no ambiente remoto.

## Testes

Para validar o cálculo de total e aplicação de desconto:

```powershell
php backend/tests/test_pedido_functions.php
```

## Deploy gratuito recomendado

1. Suba o projeto para um repositório GitHub.
2. Use um serviço gratuito de deploy de contêineres, como:
   - Render
   - Railway
   - Fly.io
3. Conecte o repositório e selecione `Dockerfile`.
4. Configure a porta `80` e publique.

> O deploy em serviços PHP gratuitos tradicionais pode exigir um contêiner Docker, por isso esta solução usa Docker para compatibilidade total.

## Instruções de acesso público

- Criar repositório GitHub
- Conectar repositório ao serviço de contêiner
- Deploy automático a partir da branch `arquitetura-sistema`
- Compartilhar link público gerado pelo serviço

## Justificativa técnica

1. Problemas resolvidos:
   - Separação clara entre interface e regras de negócio
   - Permite evolução do backend sem alterar frontend
   - Evita variáveis globais e duplicação de lógica
2. Melhoria da arquitetura:
   - Camadas de modelo, repositório, serviço e controle
   - Regras de negócio centralizadas no backend
   - Frontend apenas consome API via fetch
3. Padrões aplicados:
   - Factory: `PedidoFactory`
   - Singleton: `PedidoRepository`
   - Strategy: `DiscountStrategy` e implementações
   - Repository: `PedidoRepository`
   - Observer: `OrderSubject`/`OrderLoggerObserver`
4. Integração frontend/backend:
   - Frontend chama `/pedidos` com `fetch`
   - Backend responde JSON
   - `.htaccess` roteia API para `backend/index.php`
5. Dificuldades de deploy:
   - PHP puro precisa de servidor apropriado
   - JSON exige permissão de escrita no container
   - Hospedagem gratuita deve suportar Docker
6. Papel do Docker:
   - Garante ambiente PHP consistente
   - Faz o sistema funcionar localmente e em nuvem sem ajustes
   - Permite usar Apache e rodar o backend + frontend juntos
