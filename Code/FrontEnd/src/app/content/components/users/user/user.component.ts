
import {Component, OnInit, ChangeDetectionStrategy, ViewChild} from '@angular/core';
import { Params, ActivatedRoute, Router } from '@angular/router';
import { NgForm } from '@angular/forms';
import { User } from '../../../../core/models/user';
import { CrudService } from '../../../../core/services/shared/crud.service';
import { Role } from './../../../../core/models/role';
import { forkJoin, Observable } from 'rxjs';

@Component({
	selector: 'm-user',
	templateUrl: './user.component.html',
	changeDetection: ChangeDetectionStrategy.Default
 })
 export class UserComponent implements OnInit {
    user = new User();
    submitted: boolean = false;
    userId: number;
    edit: boolean = false;
	p1: Observable<Role[]>;
	errors: Array<String>;
	roles: Array<Role> = [];

	constructor(private _crudService: CrudService, private route: ActivatedRoute, private router: Router) {

	}

	ngOnInit() {
		this.getRoles();
		this.route.params.subscribe(params => {
			forkJoin([this.p1])
				.subscribe(data => {
					this.roles = data[0];
					if (params['id']) {
						this.userId = +params['id']; // (+) converts string 'id' to a number
						this._crudService.get<User>('users', this.userId).subscribe(
							res => {
								this.user = res;
							},
							error => {
								console.log('error');
							});
					}
				},
					error => {
						console.log('error');
					});
		});

	}

    redirect() {
        this.router.navigate(['/users']);
    }

    hasError(userForm: NgForm, field: string, validation: string) {
		if (userForm && Object.keys(userForm.form.controls).length > 0 &&
		userForm.form.controls[field].errors && validation in userForm.form.controls[field].errors) {
            if (validation) {
				return (userForm.form.controls[field].dirty &&
					userForm.form.controls[field].errors[validation]) || (this.edit && userForm.form.controls[field].errors[validation]);
            }
			return (userForm.form.controls[field].dirty &&
				userForm.form.controls[field].invalid) || (this.edit && userForm.form.controls[field].invalid);
        }
	}

    save(userForm: NgForm) {
        this.submitted = true;
        this.edit = true;

        if (userForm.valid) { // submit form if valid
            if (this.userId) { // if edit
                this._crudService.edit<User>('users', this.user, this.userId).subscribe(
                    data => {
						this.router.navigate(['/users']);
                    },
                    error => {
                        this.submitted = false;
                        if ( error.error_code === 3) {
                            this.errors = error.message;
                        }
                    });
            } else { // if add

                this._crudService.add<User>('users', this.user).subscribe(
                    data => {
						this.router.navigate(['/users']);
                    },
                    error => {
                        this.submitted = false;
                         if ( error.error_code === 3) {
                            this.errors = error.message;
                        }
                    });
            }
        } else {
            this.submitted = false;
        }
	}

	getRoles() {
		this.p1 = this._crudService.getList<Role>('roles');
	}
}
