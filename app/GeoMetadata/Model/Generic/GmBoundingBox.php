<?php
/* 
 * Copyright 2014/15 Matthias Mohr
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GeoMetadata\Model\Generic;

class GmBoundingBox implements \GeoMetadata\Model\BoundingBox {
	
	protected $north;
	protected $east;
	protected $source;
	protected $west;
	
	public function __construct() {
	}
	
	public static function create() {
		return new static();
	}
	
	public function getNorth() {
		return $this->north;
	}

	public function setNorth($north) {
		$this->north = $north;
		return $this;
	}
	
	public function getEast() {
		return $this->east;
		
	}

	public function setEast($east) {
		$this->east = $east;
		return $this;
	}
	
	public function getSouth() {
		return $this->south;
		
	}

	public function setSouth($south) {
		$this->south = $south;
		return $this;
	}
	
	public function getWest() {
		return $this->west;
	}

	public function setWest($west) {
		$this->west = $west;
		return $this;
	}

	public function getArray() {
		return array(array($this->west, $this->north), array($this->east, $this->south));
	}
	
	public function toWkt() {
		return "POLYGON(({$this->west} {$this->north},{$this->west} {$this->south},{$this->east} {$this->south},{$this->east} {$this->north},{$this->west} {$this->north}))";
	}
	
	public function __toString() {
		return $this->toWkt();
	}

}