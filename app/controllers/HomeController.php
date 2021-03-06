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
 * Controller that handles the externally accessible routes and renders the actual page to the visitor.
 */
class HomeController extends BaseController {

	public function showFrontpage()
	{
		return View::make('frontpage');
	}
	
	public function showSearch($hash)
	{
		return View::make('frontpage');
	}
	
	public function showGeodata($geodata)
	{
		return View::make('frontpage');
	}
	
	public function showComment($geodata, $comment)
	{
		return View::make('frontpage');
	}

}
