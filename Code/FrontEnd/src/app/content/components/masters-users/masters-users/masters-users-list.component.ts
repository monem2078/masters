import {Component, OnInit, ChangeDetectionStrategy, ViewChild} from '@angular/core';
import {MatTableDataSource} from '@angular/material';

export interface users {
	username: string;
	Gender: string;
	OS: string;
	source: string;
	Location: string;
  }
  
  const ELEMENT_DATA: users[] = [
	{ username: "Ahmed", source: 'Facebook', Gender: "Male", OS: 'Android', Location: 'Alexandria, Egypt'},
		{username: "Ahmed", source: 'Facebook', Gender: "Male", OS: 'Android', Location: 'Alexandria, Egypt'}
  ];


@Component({
    selector: 'm-masters-users-list',
    templateUrl: './masters-users-list.component.html',
    changeDetection: ChangeDetectionStrategy.Default
})

export class MastersUsersListComponent implements OnInit {
	displayedColumns: string[] = ['username', 'source', 'OS', 'Gender', 'Mobile', 'Location', 'actions'];
  	dataSource = ELEMENT_DATA;
	// dataSource: Array<any> = [];


	

	constructor() { }


	ngOnInit() {

	}


}




