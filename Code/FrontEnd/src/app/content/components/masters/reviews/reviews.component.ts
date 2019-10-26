import { Component, OnInit } from '@angular/core';
import {Master} from "../../../../core/models/master";
import {environment} from "../../../../../environments/environment";
import {ActivatedRoute, Router} from "@angular/router";
import {CrudService} from "../../../../core/services/shared/crud.service";

@Component({
	selector: 'm-reviews',
	templateUrl: 'reviews.component.html'
})

export class ReviewsComponent implements OnInit {

	public master: Master;
	public numbers;
	public environment = environment;

	constructor(private router: Router, private route: ActivatedRoute,
				private crudService: CrudService
	) {
		this.master = new Master();
	}
	ngOnInit() {
		this.route.params.subscribe(params => {
			this.getMaster(params['id']);
		});
	}

	getMaster (id: number) {
		this.crudService.get('admin/masters', id).subscribe((result: Master) => {
			this.master = result;
		}, err => {
		});
	}

	rateNumber(rate: number) {
		this.numbers = Array(rate).fill(4);
		return this.numbers
	}
}
