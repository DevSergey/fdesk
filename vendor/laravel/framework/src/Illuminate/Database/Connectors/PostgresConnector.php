<?php namespace Illuminate\Database\Connectors;
use PDO;
class PostgresConnector extends Connector implements ConnectorInterface {
	protected $options = array(
		PDO::ATTR_CASE => PDO::CASE_NATURAL,
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
		PDO::ATTR_STRINGIFY_FETCHES => false,
	);
	public function connect(array $config)
	{
		$dsn = $this->getDsn($config);
		$options = $this->getOptions($config);
		$connection = $this->createConnection($dsn, $config, $options);
		$charset = $config['charset'];
		$connection->prepare("set names '$charset'")->execute();
		if (isset($config['timezone']))
		{
			$timezone = $config['timezone'];
			$connection->prepare("set time zone '$timezone'")->execute();
		}
		if (isset($config['schema']))
		{
			$schema = $config['schema'];
			$connection->prepare("set search_path to \"{$schema}\"")->execute();
		}
		return $connection;
	}
	protected function getDsn(array $config)
	{
		extract($config);
		$host = isset($host) ? "host={$host};" : '';
		$dsn = "pgsql:{$host}dbname={$database}";
		if (isset($config['port']))
		{
			$dsn .= ";port={$port}";
		}
		if (isset($config['sslmode']))
		{
			$dsn .= ";sslmode={$sslmode}";
		}
		return $dsn;
	}
}
