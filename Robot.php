<?php

class Robot {
	
	private int $x;
	private int $y;
	private String $direction;
	private $validDirections = array(
		"north",
		"south",
		"east",
		"west",
	);
	private $isOnTable = false;
	public function __construct() {
	}
	
	
	public function move() {
		if(! $this->nextStepIsSafe() ) {
			echo "WARNING! Next step is unsafe. Not moving.\n";
			return;
		}
		switch($this->direction) {
			case "north":
				$this->y ++;
				break;
			case "south":
				$this->y --;
				break;
			case "east":
				$this->x ++;
				break;
			case "west":
				$this->x --;
				break;
			default:
				break;
		}
	}
	
	public function left() {
		switch($this->direction) {
			case "north":
				$this->direction = "west";
				break;
			case "south":
				$this->direction = "east";
				break;
			case "east":
				$this->direction = "north";
				break;
			case "west":
				$this->direction = "south";
				break;
			
		}
	}
	
	public function right() {
		switch($this->direction) {
			case "north":
				$this->direction = "east";
				break;
			case "south":
				$this->direction = "west";
				break;
			case "east":
				$this->direction = "south";
				break;
			case "west":
				$this->direction = "north";
				break;
			
		}
	}
	
	
	public function report() {
		echo $this->x . "," . $this->y . "," . $this->direction . "\n";
	}
	
	public function nextStepIsSafe() {
		switch($this->direction) {
			case "north":
				return $this->y < 4;
			case "south":
				return $this->y > 0;
			case "east":
				return $this->x < 4;
			case "west":
				return $this->x > 0;
		}
	}
	
	public function place($x, $y, $direction) {
		if($x > 4 || $y > 4) {
			throw new Exception("Invalid coordinates! x and y must both be less than 5.\n");
		}
		if(!in_array($direction, $this->validDirections)) {
			throw new Exception("Invalid direction! Must be one of " . implode(", ", $this->validDirections)."\n");
		}
		$this->x = $x;
		$this->y = $y;
		$this->direction = $direction;
		$this->isOnTable = true;
	}

	public function run() {
		$usage = "Valid commands:\n
			PLACE X,Y,F\n
			MOVE\n
			LEFT\n
			RIGHT\n
			REPORT\n";
		echo $usage;
		while(true) {
			$userInput = trim(fgets(STDIN)); 
			$inputArray = explode(" ", $userInput);
			$command = strtolower($inputArray[0]);
			if(!$this->isOnTable && $command != "place") {
				echo "Robot not on table! Please place on table first, with PLACE command.\n";
				continue;
			}
			switch($command) {
			case "place":
				try {
					$parameters = explode(",", $inputArray[1]);
					$x = (int)$parameters[0];
					$y = (int)$parameters[1];
					$direction = strtolower($parameters[2]);
				} catch(Exception $e) {
					echo "Invalid parameters! PLACE accepts parameters X,Y,F\n";
					break;
				}
				try {
					$this->place($x, $y, $direction);
				} catch(Exception $e) {
					echo $e->getMessage();
				}
				break;
			case "move":
				$this->move();
				break;
			case "left":
				$this->left();
				break;
			case "right":
				$this->right();
				break;
			case "report":
				$this->report();
				break;
			default:
				echo "Invalid command given!\n";
				echo $usage;
				break;
			}
		}
	}
}




?>
