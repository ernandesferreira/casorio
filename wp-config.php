<?php
/** 
 * As configurações básicas do WordPress.
 *
 * Esse arquivo contém as seguintes configurações: configurações de MySQL, Prefixo de Tabelas,
 * Chaves secretas, Idioma do WordPress, e ABSPATH. Você pode encontrar mais informações
 * visitando {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. Você pode obter as configurações de MySQL de seu servidor de hospedagem.
 *
 * Esse arquivo é usado pelo script ed criação wp-config.php durante a
 * instalação. Você não precisa usar o site, você pode apenas salvar esse arquivo
 * como "wp-config.php" e preencher os valores.
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar essas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define('DB_NAME', 'casorio.local');

/** Usuário do banco de dados MySQL */
define('DB_USER', 'root');

/** Senha do banco de dados MySQL */
define('DB_PASSWORD', 'root');

/** nome do host do MySQL */
define('DB_HOST', 'localhost');

/** Conjunto de caracteres do banco de dados a ser usado na criação das tabelas. */
define('DB_CHARSET', 'utf8mb4');

/** O tipo de collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Você pode alterá-las a qualquer momento para desvalidar quaisquer cookies existentes. Isto irá forçar todos os usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'ED|ZX|Z@plc=J/,;4#Eq?y}Zh8gFhIN(3N9t3=7W2bU`^#gO)_`EW /U{+_9ky0[');
define('SECURE_AUTH_KEY',  '*(l;i!LD|DDk-Jh>tt]/T|>zJmh;=)yrj]E2U|;eLlue|0m,R1Qt|17NPA8-^W2s');
define('LOGGED_IN_KEY',    'r{fkQkL-~,L0 @IHm$jTZFih$H$;l#GC;q9H:K?)Om(UBN>(pvS2xW[4*F7pxsjX');
define('NONCE_KEY',        'j2#qd>IEXszi7q Yl>Kg?y:8N-&o}S#5nmz2&pV.!}qs+365Df-)C;|x&}P}y1L3');
define('AUTH_SALT',        '6/9kcu%7d{^_NIr^:r4jA13voQ%1<=Fso+;jp(-uL=j,Yy4rq_q)N/By7cB%V7/R');
define('SECURE_AUTH_SALT', ';Q<eBpMD9MS+5VI18em+P!9I ~dQ!TUeBZ)JL`T2O8el2N+~ 1X?.94kVOguNTyT');
define('LOGGED_IN_SALT',   '<4h1r@>Y:~Tx B1j=K&;J)T-(k8f8bDapuH;xf}h JHOXa6_7sc*4+ [KR6+JArO');
define('NONCE_SALT',       'ZnQ><+V>UUe_RUhx,<mCZX6=+CjuIzU0Ft|a[YxleF~ -OTtSpTwZwB@0dIR]UL?');

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der para cada um um único
 * prefixo. Somente números, letras e sublinhados!
 */
$table_prefix  = 'wp_';


/**
 * Para desenvolvedores: Modo debugging WordPress.
 *
 * altere isto para true para ativar a exibição de avisos durante o desenvolvimento.
 * é altamente recomendável que os desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 */
define('WP_DEBUG', false);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
	
/** Configura as variáveis do WordPress e arquivos inclusos. */
require_once(ABSPATH . 'wp-settings.php');
