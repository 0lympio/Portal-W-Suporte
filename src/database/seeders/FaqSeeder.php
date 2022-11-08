<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::create([
            'user_id' => 1,
            'title' => 'Como faço para desativar o serviço abastece',
            'thumb' => './images/faqs/como_faco_para_desativar_o_servico_abastece.jpg',
            'description' => null,
            'content' => '<p>Para desativar o serviço de abastecimento no seu plano débito em conta, acesse <a href="https://minhaconta.semparar.com.br/minhaconta/#/login" target="_blank" rel="noreferrer noopener">Minha conta</a>:</p>
            <ul>
            <li>No Menu selecione a opção <strong>Serviços Disponíveis</strong></li>
            <li>Escolha a opção<strong> Abastecimento em Postos Conveniados<br></strong></li>
            <li>Clique em<strong> Desativar</strong></li>
            </ul>
            <p>Você também pode desativar o serviço através da nossa <strong>Central de relacionamento 4002 1552 (capitais) – opção nº 6, ou 0800 015 0252 (demais localidades).</strong></p>
            <p>Para planos com pagamento através cartão de crédito o serviço de abastecimento não poderá ser desativado separadamente, pois faz parte do pacote de serviços oferecidos pelo plano, assim como pedágios, estacionamentos e demais razões de uso. 😉</p>',
            'slug' => 'como-faco-para-desativar-o-servico-abastece',
            'published_at' => now(),
            'category_id' => '4',
            'extras' => ['type' => 'Fique ON'],
            'status_id' => 1,
        ]);

        Post::create([
            'user_id' => 1,
            'title' => 'Cancelar o meu Sem Parar',
            'thumb' => './images/faqs/cancelar_o_meu_sem_parar.png',
            'description' => null,

            'content' => '<h3>Quero cancelar o meu Sem Parar. O que devo fazer?</h3>
            <p>Para cancelar o seu dispositivo, entre em contato com nossa Central de Relacionamento ao Cliente, nos <strong>telefones 4002-1552 – capitais e 0800 015 0252 – demais regiões </strong>e digite a opção 7<strong>. 😉</strong></p>',

            'slug' => 'cancelar-o-meu-sem-parar',
            'published_at' => now(),
            'category_id' => '4',
            'extras' => ['type' => 'Fique ON'],
            'status_id' => 1,
        ]);

        Post::create([
            'user_id' => 1,
            'title' => 'Débito com o Sem Parar',
            'thumb' => './images/faqs/debito_com_o_sem_parar.jpg',
            'description' => null,

            'content' => '<p>Possui um débito com o Sem Parar e deseja resolver? É simples, você pode emitir o boleto <strong><a href="https://www.semparar.com.br/boleto" target="_blank" rel="noreferrer noopener">clicando aqui</a></strong> ou seguindo os passos abaixo. </p>
            <ul>
            <li>Acesse o <strong><a href="https://minhaconta.semparar.com.br/minhaconta/#/login" target="_blank" rel="noreferrer noopener">Minha Conta</a> </strong>com seu CPF e senha</li>
            <li>Na página principal clique no botão <strong>Pagar com boleto, cartão ou pix</strong> localizado no canto inferior esquerdo da tela</li>
            </ul>',

            'slug' => 'debito-com-o-sem-parar',
            'published_at' => now(),
            'category_id' => '4',
            'extras' => ['type' => 'Fique ON'],
            'status_id' => 1,
        ]);

        Post::create([
            'user_id' => 1,
            'title' => 'Segunda via do meu Boleto!',
            'thumb' => './images/faqs/segunda_via_do_meu_boleto.png',
            'description' => null,

            'content' => '<h3>Quero a segunda via do meu Boleto!</h3>
            <p>Você pode emitir o boleto no <a href="https://minhaconta.semparar.com.br/index.html#/login" target="_blank" rel="noreferrer noopener">Minha conta,</a> <a href="https://play.google.com/store/apps/details?id=com.semparar.semparar.minhaconta2018&amp;hl=pt" target="_blank" rel="noreferrer noopener">Aplicativo</a> e aqui no canal <a href="https://www.semparar.com.br/boleto" target="_blank" rel="noreferrer noopener">Boleto Fácil</a>!</p>
            <ul>
            <li>Acesse <a href="https://www.semparar.com.br/boleto" target="_blank" rel="noreferrer noopener"><strong>Boleto Fáci</strong><strong>l,</strong> </a>informe seu CPF    </li>
            <li>Selecione “<strong>Não sou um Robô</strong>” </li>
            <li>Clique em <strong>continuar</strong> e escolha a melhor forma para pagar, copiando o código ou baixando o boleto no seu computador, celular ou tablet. 😉</li>
            </ul>
            <p><strong>Importante</strong>:  Este canal emite boletos com até 3 dias de vencido para cliente Pessoa Física e não realiza recargas.</p>',

            'slug' => 'segunda-via-do-meu-boleto',
            'published_at' => now(),
            'category_id' => '4',
            'extras' => ['type' => 'Fique ON'],
            'status_id' => 1,
        ]);
    }
}
