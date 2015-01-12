<?

$path = dirname(dirname(__FILE__));
require ($path . '/model/config.php');
require (PATH . '/view/inc/functions.php');
require (PATH . '/dal/UserDal.php');
require (PATH . '/dal/CupDal.php');
require (PATH . '/dal/DivisionDal.php');
require (PATH . '/dal/TeamDal.php');
require (PATH . '/dal/Dal.php');
require (PATH . '/model/User.php');
require (PATH . '/model/Division.php');
require (PATH . '/model/Team.php');
require (PATH . '/model/Cup.php');
require (PATH . '/model/Helper.php');
global $LOGGED_IN;
global $CURRENT_USER; //Den inloggade användarens objekt.
$LOGGED_IN = false;


class Controller
{
	public $viewFunc;
	public $dal;
	public $dbh;
	private $userDal;
	private $cupDal;
	private $divisionDal;
	private $teamDal;
	private $helper;

	public function __construct() {
		$this->viewFunc = New ViewFunc;
		$this->dal = New Dal;
		$this->dbh = $this->dal->dbHandle();
		$this->userDal = New UserDal($this->dbh);
		$this->cupDal = New CupDal($this->dbh);
		$this->divisionDal = New DivisionDal($this->dbh);
		$this->teamDal = New TeamDal($this->dbh);
		$this->helper = New Helper($this);
	}

	//---------------View functions--------------
	//  			( . Y . )

	public function getHeader() {
		return $this->viewFunc->getHeader();
	}

	public function getSidebarRight() {
		return $this->viewFunc->getSidebarRight();
	}

	public function getFooter() {
		return $this->viewFunc->getFooter();
	}

	/**
	*Echos the path given based on webserver root
	*@param $subdir Path to document from site root.
	*/
	public function getURL($subDir) {
		echo $this->viewFunc->getUrl($subDir);
	}

	//----------------Database functions----------

	public function getDbh() {
		return $this->dal->dbHandle();
	}

	public function save($obj) {
		$this->dal->save($obj, $this->dbh);
	}

	/*==================User======================*/
	public function getUser($userId) {
		return $this->userDal->getUser($userId);
	}

	/*===================Cup======================*/
	public function getAllCups() {
		return $this->cupDal->getAllCups();
	}

	public function getCupEager($cupId) {
		$cup = $this->cupDal->getCup($cupId);

		$cup->divisionList = $this->divisionDal->getDivisionsForCup($cup->cupId);

		foreach($cup->divisionList as $division) {
			$division->teamList = $this->teamDal->getTeamsForDivision($division->divisionId);
		}

		return $cup;
	}

	/**
	 * Returns a boolean indicating if the username and password combination was found and correct
	 * @param $email User email
	 * @param $pwd User password
	 */
	public function checkLogin($email, $pwd) {
		return $this->helper->checkLogin($email, $pwd);
	}

	//==================HELPER========================
	public function checkLoggedInCookie() {
		$this->helper->checkLoggedInCookie();
	}

	public function login($email, $pwd) {
		$this->helper->login($email, $pwd);
	}

	public function logout() {
		$this->helper->logout();
	}

	public function setAccessLevel($lvl) {
		$this->helper->setAccessLevel($lvl);
	}

}


?>