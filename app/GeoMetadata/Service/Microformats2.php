<?php
/* 
 * Copyright 2014 Matthias Mohr
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

namespace GeoMetadata\Service;

use Mf2;

class Microformats2 implements Parser {
	
	protected $cache = array();
	
	/**
	 * Returns the internal name of the parser.
	 * 
	 * Should be unique across all parsers.
	 * 
	 * @return string Internal type name of the parser.
	 */
	public function getType() {
		return 'mf2';
	}
	
	/**
	 * Returns the displayable name of the parser.
	 * 
	 * @return string Name of the parser
	 */
	public function getName() {
		return 'microformats2';
	}
	
	/**
	 * Quickly checks whether the given content is compatible to the parser.
	 * 
	 * @param string $source String containing the data to parse.
	 * @return boolean true if content can be parsed, false if not.
	 */
	public function detect($source) {
		$json = $this->getJSON($source);
		return !empty($json['items']);
	}

	/**
	 * Parses the data.
	 * 
	 * @param string $source String containing the data to parse.
	 * @param GeoMetadata\Model\Metadata $model An instance of a model class to fill with the data.
	 * @return boolean true on success, false on failure.
	 */
	public function parse($source, \GeoMetadata\Model\Metadata &$model) {
		$json = $this->getJSON($source);

		if (!isset($json['items'])) {
			// No data at all, return
			return false;
		}
		
		// Get all latitude and longitude values to calculate a bbox from them
		$lat = array();
		$lon = array();
		foreach ($json['items'] as $item) {
			if (isset($item['properties']) && !empty($item['properties']['latitude']) && !empty($item['properties']['longitude'])) {
				$lat[] = current($item['properties']['latitude']);
				$lon[] = current($item['properties']['longitude']);
			}
		}
		
		if (count($lat) == 0 || count($lon) == 0) {
			// No geodata available, return
			return false;
		}
		
		// Build the bounding box from the lon/lat values
		$model->createBoundingBox(min($lon), min($lat), max($lon), max($lat));
		
		// Trying to parse additional meta data
		if (isset($json['rels'])) {
			foreach ($json['rels'] as $key => $value) {
				switch(strtolower($key)) {
					case 'copyright':
						$model->setCopyright(current($value));
						break;
					case 'tag':
						$model->setKeywords(current($value));
						break;
					case 'contents':
						$model->setAbstract(current($value));
						break;
					case 'license':
						$model->setLicense(current($value));
						break;
					case 'author':
						$model->setAuthor(current($value));
						break;
				}
			}
		}

		return true;
	}
	
	protected function getJSON($source) {
		$checksum = crc32($source);
		if (isset($this->cache[$checksum])) {
			return $this->cache[$checksum];
		}
		else {
			$json = Mf2\parse($source);
			$this->cache[$checksum] = $json;
			return $json;
		}
	}
	
}