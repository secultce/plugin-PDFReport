# Plugin para gerar PDF

Esse plugin é destinado para geração de PDF's com relatórios dentro da instãncia do [Mapa da Saude](https://mapa.sus.ce.gov.br/), que é mesma utilização da plataforma [ Mapas Culturais](https://github.com/mapasculturais/mapasculturais), um projeto Open Source disponível para toda comunidade usar e colaborar.

### Instalação
Após fazer o clone para dentro do repositório de plugins, deve atualizar todas as dependências do composer.json utilizando o comando `composer update` ou `composer install` , caso não tenha, então tem que instalar o composer.
Na eventualidade de tiver usando o container com [base na estrutura](https://github.com/mapasculturais/mapasculturais-base-project) indicada pelo mapas culturais, então basta apenas entrar no container e ir até o repositório do plugins e rodar o comando acima.
Logo após a instalação e atualizar as dependências, então deve rodar o comando de permissão para toda a pasta vendor do plugin.
`chmod -R 777 vendor`

### Obs

- Para mais informação do uso, instalação e atualização de plugins dentro do Mapas Culturais, é só seguir a documentação [aqui](https://wiki.mapasculturais.org/books/formacao-para-desenvolvedores/page/plugins).
- As rotas são feita dentro do controlador que está em Controllers/Pdf.php
- O Plugin se baseia no pacote de php chamado **Mpdf** que se encontra [nesse repositório](https://github.com/mpdf/mpdf)

Eu tenho mais [^1] pra dizer. [^1]: diga aqui.

