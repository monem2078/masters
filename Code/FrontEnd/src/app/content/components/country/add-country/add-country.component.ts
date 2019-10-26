import { Component, OnInit, ChangeDetectionStrategy } from '@angular/core';
import { Params, ActivatedRoute, Router } from '@angular/router';
import { NgForm } from '@angular/forms';

// Services
import { CrudService } from '../../../../core/services/shared/crud.service';


//rxjs
import { forkJoin, Observable } from 'rxjs';

// Material
@Component({
    selector: 'm-add-country',
    templateUrl: './add-country.component.html',
    changeDetection: ChangeDetectionStrategy.Default
})

export class AddCountryComponent implements OnInit {

   
	constructor() { }

	ngOnInit() {
     
    }



  }
