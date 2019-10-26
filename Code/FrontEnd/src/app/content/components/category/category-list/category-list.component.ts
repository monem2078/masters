import { Component, OnInit, ChangeDetectionStrategy, ViewChild } from '@angular/core';


export interface categories {
	name: string;
	arName: string;
	order: string;
  }

  const ELEMENT_DATA: categories[] = [
	{ name: 'Art', arName: 'فن', order: '1'},
	{ name: 'Sports', arName: 'رياضة', order: '2'},
    { name: 'Music', arName: 'موسيقى', order: '3'}
  ]
@Component({
    selector: 'm-category-list',
    templateUrl: './category-list.component.html',
    changeDetection: ChangeDetectionStrategy.Default
})

export class CategoryListComponent implements OnInit {

    
	displayedColumns = [ 'name', 'arName', 'order', 'actions'];
	dataSource = ELEMENT_DATA;


	constructor() { }

	ngOnInit() {
   
    }
    
 
 



  }
