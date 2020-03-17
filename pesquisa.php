<?php 
  include('conexaoAdapter.php');

  // Pega o codigo do cliente digitado pelo usuário
  $cliente = mysqli_real_escape_string($conexao, $_POST['nome']);

  // Query que busca os atendimentos do cliente no Banco
  $query = "SELECT
                cli.IDCliente AS codigo,
                cli.Nome AS cliente,
                ate.NumeroProtocolo AS protocolo,
                ate.DataAbertura AS dataAbertura,
                ate.Descricao AS descricao,

              IF (
                DATE(ate.DataAbertura) <= DATE_SUB(CURDATE(), INTERVAL 60 DAY),
                'verde',

              IF (
                DATE(ate.DataAbertura) > DATE_SUB(CURDATE(), INTERVAL 59 DAY)
                AND DATE(ate.DataAbertura) <= DATE_SUB(CURDATE(), INTERVAL 16 DAY),
                'amarelo',
                'vermelho'
              )) AS status
              FROM
                TB_Atendimento ate
              INNER JOIN TB_Contrato cont ON cont.IDContrato = ate.IDContrato
              INNER JOIN TB_Cliente cli ON cli.IDCliente = cont.IDCliente
              INNER JOIN TB_TipoAtendimento tipo ON tipo.IDTipoAtendimento = ate.IDTipoAtendimento
              WHERE
                cli.IDCliente = $cliente
              AND tipo.TipoServico = 'SUPORTE'

              ORDER BY ate.DataAbertura DESC";
  // Fim
  
  // Executa a query
  $result = mysqli_query($conexao, $query);
  // Fim

  // Começa a escrever o HTML
  $mensagem = "<html lang='pt-BR'>
                <title>
                  Produtos em Quantidade Mínima
                </title>
                ";
  //CSS
  $mensagem .= " 
                <head>
                  <meta charset='utf-8'>
                  <style type='text/css'>
                                        
                    tr td {
                      font-family: arial;
                      vertical-align: center;
                      text-align: center;
                      border:1px solid #B6E3FC;
                      text-decoration: none;
                      overflow: hidden;
                      padding: 5px 10px 5px 10px;
                      position: relative;
                    }
                      
                    table {
                      width:100%;
                      border-collapse: collapse;
                      border-spacing: 0;
                    }
                    
                    .titulo{
                      font-family: arial;
                      font-weight: bold;
                      border:1px solid #B6E3FC;
                      background-color: #B6E3FC;
                    }
                    
                    .cabecalho{
                      font-weight: bold;
                      border:1px solid #B6E3FC;
                      background-color: #B6E3FC;
                    }
                    
                    p{
                      margin-top: 17px;
                      margin-right: 65px;
                    }

                    .botao {
                      margin-top: 10px;
                    }

                    html {
                      margin: 20px;
                    }

                    .image {
                      width: 50px;
                      height: 50px;
                    }

                    .image2{
                      width: 20px;
                      height: 20px;
                    }

                  </style>
                  <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' integrity='sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO' crossorigin='anonymous'>
                </head>
                ";
  $mensagem.=	"
      <body>
        <font size='3.5px'; family='arial'>
          Olá,<br/>
          Segue relação de atendimentos recorrentes do cliente<br /><br />
          <img class='image2' src='verde.png'> - Atendimento com mais de 60 dias <br />
          <img class='image2' src='amarelo.png'> - Atendimento entre 16 e 59 dias <br />
          <img class='image2' src='vermelho.png'> - Atendimento com menos de 15 dias <br />
        </font>
        <form action='index.php'>
          <input type='submit' value='Voltar' class='btn btn-primary botao' />
        </form>
        <table style='border:1px #B6E3FC; border-bottom-style:solid;'>
          <tr style='font-size: 13px;' class='cabecalho'>
            <td>HUMOR</td>
            <td>CODIGO CLIENTE</td>
            <td>CLIENTE</td>
            <td>PROTOCOLO</td>	
            <td>DATA ABERTURA</td>
            <td>DESCRICAO</td>
          </tr>";

  while($escrever = mysqli_fetch_array($result)) {
    
    $mensagem.= '
              <tr style="font-size: 13px;" class="linha">
                <td><img class="image" src="'.$escrever["status"].'.png"></td>
                <td><a target="_blank" href="http://adapter.vero.int/adapter/#/comercial/clientes/'.$escrever["codigo"].'/atendimentos">' . $escrever["codigo"] . '</a></td>
								<td>' . $escrever["cliente"] . '</td>
								<td><a>' . $escrever["protocolo"] . '</td>
								<td>' . $escrever["dataAbertura"] . '</td>
								<td>' . $escrever["descricao"] . '</td>
							</tr>';
    }

    $mensagem.= "	</table>
					</body>
        </html>"; 
  // Fim
  
  // Mostra o HTML na tela
  echo $mensagem;
  // Fim

?>