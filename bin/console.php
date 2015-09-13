<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

require_once 'vendor/autoload.php';

$helperSet = null;

define('BASEPATH', 'development');

if(getenv('PHP_ENV')){
	define('ENVIRONMENT', getenv('PHP_ENV'));
}else{
	define('ENVIRONMENT', 'development');
}

define('APPPATH', 'application/');
define('EXT', '.php');

require_once APPPATH."libraries/doctrine.php";

$doctrine = new Doctrine();

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
		'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($doctrine->em->getConnection()),
		'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($doctrine->em),
		'dialog' => new \Symfony\Component\Console\Helper\DialogHelper()
));

$cli = new Symfony\Component\Console\Application('Yarsha Command Line Interface', \Doctrine\ORM\Version::VERSION);

$helperSet = ($helperSet) ?: new \Symfony\Component\Console\Helper\HelperSet();

$cli->setCatchExceptions(true);
$cli->setHelperSet($helperSet);

$cli->addCommands(array(
		// Migrations Commands
		new \Yarsha\DBAL\Migrations\Tools\Console\Command\CustomDiffCommand(),
		new \Yarsha\DBAL\Migrations\Tools\Console\Command\InitDbCommand(),
		new \Yarsha\DBAL\Migrations\Tools\Console\Command\DbFixtureCommand(),
		new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand(),
		new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand(),
		new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand(),
		new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand(),
		new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand()
));

\Doctrine\ORM\Tools\Console\ConsoleRunner::addCommands($cli);
$cli->run();

