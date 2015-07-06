<?php
require_once 'log4php/Logger.php';
require_once 'Psr/Log/LoggerInterface.php';

/**
 * FATAL - enviar e-mail, arquivo
 * ERROR - arquivo
 * WARN - arquivo
 * INFO - arquivo
 * DEBUG - um arquivo por dia
 * TRACE - arquivo
 */

class Logger4PHP implements \Psr\Log\LoggerInterface {

	/**
	 * @var Logger
	 */
	private $logger;

	public function __construct($logger = 'default') {
		$pathXML = LIB . DS . 'log' . DS . 'config.xml';
		
		$configurator = new LoggerConfiguratorDefault();
		$config = $configurator->parse($pathXML);
		Logger::configure($config);
		$this->logger = Logger::getLogger($logger);
	}

	public function setLogger($logger) {
		$this->logger = Logger::getLogger($logger);
		return $this;
	}

	/**
	 * O sistema está inutilizável.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function emergency($message, array $context = array()) {
		$this->logger->fatal($this->interpolate($message, $context));
	}

	/**
	 * Ação deve ser tomada imediatamente.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function alert($message, array $context = array()) {
		$this->logger->fatal($this->interpolate($message, $context));
	}

	/**
	 * Condições críticas.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function critical($message, array $context = array()) {
		$this->logger->fatal($this->interpolate($message, $context));
	}

	/**
	 * Erros de execução que não requerem ação imediata, mas deve ser registrado e monitorado.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function error($message, array $context = array()) {
		$this->logger->error($this->interpolate($message, $context));
	}

	/**
	 * Ocorrências excepcionais que não são erros.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function warning($message, array $context = array()) {
		$this->logger->warn($this->interpolate($message, $context));
	}
	/**
	 * Eventos normais, mas significativa.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function notice($message, array $context = array()) {
		$this->logger->info($this->interpolate($message, $context));
	}

	/**
	 * Eventos interessantes.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function info($message, array $context = array()) {
		$this->logger->info($this->interpolate($message, $context));
	}

	/**
	 * Informações de depuração detalhada.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function debug($message, array $context = array()) {
		$this->logger->debug($this->interpolate($message, $context));
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function log($level, $message, array $context = array()) {
		throw new \Exception('Please call specific logging message');
	}

	/**
	 * Interpolates context values into the message placeholders.
	 * Taken from PSR-3's example implementation.
	 */
	protected function interpolate($message, array $context = array()) {
		// build a replacement array with braces around the context
		// keys
		$replace = array();
		foreach ($context as $key => $val) {
			$replace['{' . $key . '}'] = $val;
		}

		$message .= "\n\r";
		// interpolate replacement values into the message and return
		return strtr($message, $replace);
	}
}