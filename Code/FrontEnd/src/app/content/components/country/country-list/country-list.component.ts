import { Component, OnInit, ChangeDetectionStrategy, ViewChild } from '@angular/core';
export interface countries {
	name: string;
	arName: string;
	code: number;
	isOperating: string;
  }

  const ELEMENT_DATA: countries[] = [
	{ name: 'Egypt', arName: 'مصر', code:  +2 , isOperating: 'yes'},
	{ name: 'Saudi Arabia', arName: 'السعودية', code:  +9 , isOperating: 'yes'},
	{ name: 'Egypt', arName: 'مصر', code:  +2 , isOperating: 'yes'},
	{ name: 'Saudi Arabia', arName: 'السعودية', code:  +9 , isOperating: 'yes'},
  ]

@Component({
    selector: 'm-country-list',
    templateUrl: './country-list.component.html',
    changeDetection: ChangeDetectionStrategy.Default
})

export class CountryListComponent implements OnInit {


    
	displayedColumns = [ 'name', 'arName', 'code', 'isOperating', 'actions'];
	dataSource = ELEMENT_DATA;


	constructor() { }

	ngOnInit() {
   
    }
    
 
 



  }
