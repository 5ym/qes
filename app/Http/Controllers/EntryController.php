<?php

namespace App\Http\Controllers;

function swi($status) {
	switch($status) {
		case 0:
			return 'unpaid, unentry';
			break;
		case 1:
			return 'unpaid, entry';
			break;
		case 2:
			return 'paid, unentry';
			break;
		case 3:
			return 'paid, entry';
			break;
	}
}

class EntryController extends Controller {
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
			$res = \App\entry::where('randum', $_POST['secret']);
			switch($_POST['status']) {
				case 'pay':
					if($res->get()[0]->status<2) {
						$res->update(['status'=>$res->get()[0]->status+2]);
					} else {
						$res->update(['status'=>$res->get()[0]->status-2]);
					}
					break;
				case 'entry':
					if($res->get()[0]->status<1) {
						$res->update(['status'=>$res->get()[0]->status+1]);
					} else {
						$res->update(['status'=>$res->get()[0]->status-1]);
					}
					break;
				case 'pe':
					$res->update(['status'=>3]);
					break;
			}
			return json_encode(['status'=>swi($res->get()[0]->status)]);
		}
		public function getstatus() {
			if(!$_GET)
			 abort(403);
			$row = \App\entry::where('randum', $_GET['secret'])->get();
			return view('status')->with([
				'name'=>$row[0]->name,
				'contact'=>$row[0]->contact,
				'address'=>$row[0]->address,
				'status'=>swi($row[0]->status),
				'secret'=>$row[0]->randum
			]);
		}
		public function list() {
			return view('list')->with(['list'=>\App\entry::all()]);
		}
		public function liststatus() {
			$status = [];
			foreach(\App\entry::get(['status', 'randum']) as $value) {
				$status += [$value->randum=>swi($value->status)];
			}
			return $status;
		}
}
