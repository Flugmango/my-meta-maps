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

/**
 * Implements the comment model.
 */
class Comment extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'mmm_comment';
	
	/**
	 * Tell the ORM to use timestamp fields or not. 
	 * 
	 * @var boolean
	 */
	public $timestamps = true;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

	public function user() {
		return $this->belongsTo('User');
	}

	public function layer() {
		return $this->belongsTo('Layer');
	}
	
    public function geodata() {
        return $this->belongsTo('Geodata');
    }
	
	public function getGeomAttribute($value) {
		if (!empty($value)) {
			// TODO: THIS SHOULD BE AVOIDED IN ANY CASE! Need to change this...
			$result = DB::selectOne("SELECT ST_AsText('{$value}') AS geom");
			return $result->geom;
		}
		else {
			return null;
		}
	}

}
?>