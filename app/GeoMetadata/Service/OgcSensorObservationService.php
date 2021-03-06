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

namespace GeoMetadata\Service;

class OgcSensorObservationService extends OgcWebServicesCommon {

	public function getNamespaceUri() {
		return 'http://www.opengis.net/sos/1.0';
	}

	public function getName() {
		return 'OGC SOS';
	}

	public function getCode() {
		return 'sos';
	}
	
	protected function parseContents() {
		$data = array();

		$nodes = $this->selectMany(array('Contents', 'ObservationOfferingList', 'ObservationOffering'), null, false);
		foreach($nodes as $node) {
			$gmlNsPrefix = 'gml';

			$attributes = $this->getAttrsAsArray($node, $gmlNsPrefix);
			$ooNode = $node->children($gmlNsPrefix, true);
			
			$entry = array(
				'id' => empty($attributes['id']) ? null : $attributes['id'],
				'title' => empty($ooNode->description) ? null : strval($ooNode->description),
				'bbox' => array()
			);

			if (!empty($ooNode->boundedBy)) {
				$bbNode = $ooNode->boundedBy->children($gmlNsPrefix, true);
				if (!empty($bbNode->Envelope)) {
					$envelopeAttrs = $this->getAttrsAsArray($bbNode->Envelope); // Seems we don't need a ns prefix here
					if (isset($envelopeAttrs['srsName']) && $this->isWgs84($envelopeAttrs['srsName'])) {
						$envNode = $bbNode->Envelope->children($gmlNsPrefix, true);
						if (!empty($envNode->lowerCorner) && !empty($envNode->upperCorner)) {
							$bbox = $this->parseCoords(strval($envNode->lowerCorner), strval($envNode->upperCorner));
							if (count($bbox) == 4) {
								$entry['bbox'] = $bbox;
							}
						}
					}
				}
			}

			$data[] = $entry;
		}
		return $data;
	}

}
