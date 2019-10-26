import {Component, OnInit, ChangeDetectionStrategy, ViewChild} from '@angular/core';
import { CrudService } from '../../../../core/services/shared/crud.service';
import { Router, ActivatedRoute } from '@angular/router';
// RXJS
import { tap , finalize } from 'rxjs/operators';
import { merge , BehaviorSubject , Observable} from 'rxjs';

// Models
import { FilterObject } from '../../../../core/models/filter-object';
import { User } from '../../../../core/models/user';
import { PagedResult} from '../../../../core/models/paged-result';

// Material
import { MatPaginator, MatSort } from '@angular/material';

import { LayoutUtilsService, MessageType } from '../../../../core/services/layout-utils.service';
import { Role } from '../../../../core/models/role';
import { environment } from './../../../../../environments/environment';
@Component({
    selector: 'm-user-list',
    templateUrl: './user-list.component.html',
    changeDetection: ChangeDetectionStrategy.Default
})

export class UserListComponent implements OnInit {

	dataSource: Array<User> = [];
	roles: Array<Role> = [];
	loadingSubject = new BehaviorSubject<boolean>(false);
	loading$ = this.loadingSubject.asObservable();

	// Paginator | Paginators count
	paginatorTotal$: Observable<number>;

	filterObject = new FilterObject();

	displayedColumns = ['username', 'email', 'role', 'actions'];

	@ViewChild(MatPaginator) paginator: MatPaginator;
	@ViewChild(MatSort) sort: MatSort;
	pageSize = environment.pageSize;

	constructor(private route: ActivatedRoute, private _crudService: CrudService,
		private router: Router, private layoutUtilsService: LayoutUtilsService) { }

	/** LOAD DATA */
	ngOnInit() {
		this.filterObject.SearchObject = {};
		/* Data load will be triggered in two cases:
		- when a pagination event occurs => this.paginator.page
		- when a sort event occurs => this.sort.sortChange
		**/
		merge(this.sort.sortChange, this.paginator.page)
			.pipe(
				tap(() => {
					this.getList();
				})
			)
			.subscribe();

		this.getList();
		this.getRoles();
	}

	getList() {
		this.loadingSubject.next(true);
		this.filterObject.PageNumber = this.paginator.pageIndex + 1;
		this.filterObject.SortBy = this.sort.active;
		this.filterObject.SortDirection = this.sort.direction !== '' ? this.sort.direction : this.sort.start;
		this._crudService.getPaginatedList<PagedResult>('users', this.filterObject).
			subscribe(res => {
				this.loadingSubject.next(false);
				this.paginatorTotal$ = res.TotalRecords;
				this.dataSource = res.Results;
			},
            error => {
				this.loadingSubject.next(false);
			});
	}


	edit(id: any) {
        this.router.navigate(['/users/edit', id]);
    }

	delete(id: any) {
		const _title: string = 'User Delete';
		const _description: string = 'Are you sure to permanently delete this User?';
		const _waitDesciption: string = 'User is deleting...';
		const _deleteMessage = `User has been deleted`;

		const dialogRef = this.layoutUtilsService.deleteElement(_title, _description, _waitDesciption);
		dialogRef.afterClosed().subscribe(res => {
			if (!res) {
				return;
			}
			this._crudService.delete<User>('users', id).
            subscribe(pagedData => {
				this.layoutUtilsService.showActionNotification(_deleteMessage, MessageType.Delete);
				this.paginator.pageIndex = 0;
				this.getList();
            },
            error => {
                this.loadingSubject.next(false);
            });
		});
	}

	resetSearch = function () {
		this.filterObject.SearchObject = {};
		this.getList();
	};

	getRoles() {
		this._crudService.getList<Role>('roles').subscribe(res => {
			this.roles = res;
		});
	}


}
