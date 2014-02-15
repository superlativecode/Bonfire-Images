<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Add_ext extends Migration
{
	/**
	 * The name of the database table
	 *
	 * @var String
	 */
	private $table_name = 'images';

	/**
	 * Install this migration
	 *
	 * @return void
	 */
	public function up()
	{
		$this->dbforge->add_column($this->table_name, array(
		    'ext' => array(
		        'type' => 'VARCHAR',
		        'constraint' => 6,
    			'null' => FALSE,
    			'default' => 'jpg'
		    )
		));	
	}

	//--------------------------------------------------------------------

	/**
	 * Uninstall this migration
	 *
	 * @return void
	 */
	public function down()
	{
		$this->dbforge->drop_column($this->table_name, 'ext');
	}

	//--------------------------------------------------------------------

}