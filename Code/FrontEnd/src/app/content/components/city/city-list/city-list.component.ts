import { Component, OnInit, ChangeDetectionStrategy, ViewChild } from '@angular/core';

export interface cities {
	name: string;
	arName: string;
	country: string;
  }

  const ELEMENT_DATA: cities[] = [
	{ name: 'Alexandria', arName: 'اسكندرية', country: 'Egypt'},
	{ name: 'Cairo', arName: 'القاهرة', country: 'Egypt'},
    { name: 'Alexandria', arName: 'اسكندرية', country: 'Egypt'},
	{ name: 'Cairo', arName: 'القاهرة', country: 'Egypt'},
  ]

@Component({
    selector: 'm-city-list',
    templateUrl: './city-list.component.html',
    changeDetection: ChangeDetectionStrategy.Default
})

export class CityListComponent implements OnInit {

    
	displayedColumns = [ 'name', 'arName', 'country', 'actions'];
	dataSource = ELEMENT_DATA;

	constructor() { }

	ngOnInit() {
   
    }
    
 
 



  }
