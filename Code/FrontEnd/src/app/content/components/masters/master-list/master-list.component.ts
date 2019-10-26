import { Component, OnInit } from '@angular/core';
  // Models
import { FilterObject } from '../../../../core/models/filter-object';
import { PagedResult} from '../../../../core/models/paged-result';

// Material
import { MatPaginator, MatSort } from '@angular/material';

import { LayoutUtilsService, MessageType } from '../../../../core/services/layout-utils.service';
import { Role } from '../../../../core/models/role';
import { environment } from './../../../../../environments/environment';
import { tap , debounceTime, distinctUntilChanged, filter, switchMap, catchError} from 'rxjs/operators';
import {ViewChild } from '@angular/core';
import {Master} from '../../../../core/models/master';
import { Router, ActivatedRoute } from '@angular/router';
import { CrudService } from '../../../../core/services/shared/crud.service';
import {MasterService} from '../../../../core/services/master/master.service';
import {NgbTypeahead} from '@ng-bootstrap/ng-bootstrap';
import { BehaviorSubject, Observable, merge, Subject, of } from 'rxjs';
import {City} from '../../../../core/models/city';
import {Country} from '../../../../core/models/country';
import {CountryService} from '../../../../core/services/country/country.service';
import {Platform} from '../../../../core/models/platform';
@Component({
	selector: 'm-master-list',
	templateUrl: 'master-list.component.html'
})

export class MasterListComponent implements OnInit {

	loadingSubject = new BehaviorSubject<boolean>(false);
	loading$ = this.loadingSubject.asObservable();
	@ViewChild('agencyinstance') agencyInstance: NgbTypeahead;
	@ViewChild('clientinstance') clientInstance: NgbTypeahead;
	public cities: Array<City> = [];
	public countries: Array<Country> = [];
	public platforms: Array<Platform> = [];
	focus$ = new Subject<string>();
	click$ = new Subject<string>();
	public environment = environment;
	model: any;

	// Paginator | Paginators count
	paginatorTotal$: Observable<number>;

	filterObject = new FilterObject();

	masterSearching: boolean;
	masterSearchFailed: boolean;

	dataSource: Array<Master> = [];

	displayedColumns = [ 'name', 'mobile_no', 'overall_rating', 'os_version', 'platform_name_ar', 'country_id', 'actions'];

	@ViewChild(MatPaginator) paginator: MatPaginator;
	@ViewChild(MatSort) sort: MatSort;
	pageSize = environment.pageSize;

	constructor(private route: ActivatedRoute, private _crudService: CrudService,
		private router: Router, private layoutUtilsService: LayoutUtilsService,
				private countryService: CountryService  , private masterService: MasterService) {
		this.filterObject = new FilterObject();
		this.filterObject.PageSize = environment.pageSize;
	}

	/** LOAD DATA */
	ngOnInit() {
		this.model = {};
		this.filterObject.SearchObject = {};
		this.getMasters();
		this.getPlatforms();
		this.getCountries();

		merge(this.sort.sortChange, this.paginator.page)
			.pipe(
				tap(() => {
					this.getMasters();
				})
			)
			.subscribe();
	}

	getMasters() {
		this.loadingSubject.next(true);
		this.filterObject.PageNumber = this.paginator.pageIndex + 1;
		this.filterObject.SortBy = this.sort.active;
		this.filterObject.SortDirection = this.sort.direction;

		this._crudService.getPaginatedList('admin/masters', this.filterObject).subscribe((result: PagedResult) => {
			this.dataSource = result.Results;
			this.loadingSubject.next(false);
			this.paginatorTotal$ = result.TotalRecords;


		}, err => {
			this.loadingSubject.next(false);
		});
	}

	resetSearch() {
		this.filterObject.SearchObject = {};
		this.model.masterSearch = null;
		this.getMasters();
	}

	// auto complete client
	searchMasters = (text$: Observable<string>) => {
		const debouncedText$ = text$.pipe(debounceTime(200), distinctUntilChanged(), tap(() => {this.masterSearching = true; }));
		const clicksWithClosedPopup$ = this.click$.pipe(filter(() => !this.clientInstance.isPopupOpen()));
		const inputFocus$ = this.focus$;

		return merge(debouncedText$, inputFocus$, clicksWithClosedPopup$).pipe(
			switchMap(term => this.masterService.masterAutoComplete(term)
				.pipe(tap(() => this.masterSearchFailed = false),
					catchError(() => {
						this.masterSearchFailed = true;
						return of([]);
					}))
			),
			tap(( ) => {this.masterSearching = false; })
		);
	}

	MasterFormatter =  (result: any) =>  result.name;

	onMasterChange(event) {
		if (event.id) {
			this.model.masterSearch = event.name;
			this.filterObject.SearchObject.name = event.name;
		} else {
			this.model.masterSearch = event;
			this.filterObject.SearchObject.name = null;
		}
	}

	getCities() {
		this.countryService.cities(this.filterObject.SearchObject.country_id).subscribe((result: Array<City>) => {
			this.cities = result;
		});
	}
	getCountries() {
		this._crudService.getList('countries').subscribe((result: Array<Country>) => {
			this.countries = result;
		});
	}
	getPlatforms() {
		this._crudService.getList('platforms').subscribe((result: Array<Platform>) => {
			this.platforms = result;
		});
	}
	delete(id: any) {
		const _title: string = 'Delete Master';
		const _description: string = 'Are you sure you want to delete this master ?';
		const _waitDesciption: string = 'Deleting Master ...';
		const _deleteMessage = `Master Has Been Deleted`;

		const dialogRef = this.layoutUtilsService.deleteElement(_title, _description, _waitDesciption);
		dialogRef.afterClosed().subscribe(res => {
			if (!res) {
				return;
			}
			this._crudService.delete<Master>('admin/masters', id).
			subscribe(pagedData => {
					this.layoutUtilsService.showActionNotification(_deleteMessage, MessageType.Delete);
					this.paginator.pageIndex = 0;
					this.getMasters();
				},
				error => {
					this.loadingSubject.next(false);
				});
		});
	}
	counter(i: number) {
		return new Array(i);
	}

}
