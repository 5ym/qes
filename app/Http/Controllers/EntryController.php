<?php

namespace App\Http\Controllers;

class EntryController extends Controller
{
    public function setentry() {
			$count = 1;
			$randum = 0;
			while($count!=0) {
				$randum = rand(100000000, 999999999);
				$count = \App\entry::where('randum', $randum)->get()->count();
			}
			$flight = \App\entry::create([
				'name'=>$_POST['name'],
				'contact'=>$_POST['contact'],
				'address'=>$_POST['address'],
				'randum'=>$randum,
				'status'=>0
			]);

			return $flight;
		}
		public function getentry() {
			if(!$_GET)
			 abort(403);
			$count = \App\entry::where('randum', $_GET['secret'])->get()->count();
			if(0<$count) {
				return json_encode(['status'=>'success']);
			}
			abort(403);
		}
		public function upstatus() {
			return;
		}
		public function getstatus() {
			if(!$_GET)
			 abort(403);
			$row = \App\entry::where('randum', $_GET['secret'])->get();
			switch($row[0]->status) {
				case 0:
					$status = 'unpaid, unentry';
					break;
				case 1:
					$status = 'unpaid, entry';
					break;
				case 2:
					$status = 'paid, unentry';
					break;
				case 3:
					$status = 'paid, entry';
					break;
			}
			return view('status')->with([
				'name'=>$row[0]->name,
				'contact'=>$row[0]->contact,
				'address'=>$row[0]->address,
				'status'=>$status
			]);
		}
}
