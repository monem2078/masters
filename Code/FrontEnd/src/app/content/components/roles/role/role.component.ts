import { RoleService } from './../../../../core/services/security/roles.service';

import {Component, OnInit, ChangeDetectionStrategy, ViewChild} from '@angular/core';
import { Params, ActivatedRoute, Router } from '@angular/router';
import { NgForm } from '@angular/forms';
import { Role } from '../../../../core/models/role';
import { Module } from '../../../../core/models/module';
import { CrudService } from '../../../../core/services/shared/crud.service';
import { forkJoin, Observable } from 'rxjs';

@Component({
   selector: 'm-role',
   templateUrl: './role.component.html',
   changeDetection: ChangeDetectionStrategy.Default
})
export class RoleComponent implements OnInit {
    role = new Role();
    submitted: boolean = false;
    roleId: number;
    edit: boolean = false;
	p1: Observable<Module[]>;
	errors: Array<String>;
	modules: Array<Module> = [];

	constructor(private _crudService: CrudService, private route: ActivatedRoute, private router: Router , private roleService: RoleService) {

    }
	ngOnInit() {
		this.role.rights = [];
		this.getRights();
		this.route.params.subscribe(params => {
				forkJoin([this.p1])
					.subscribe(data => {
						this.modules = data[0];
						if (params['id']) {
							this.roleId = +params['id']; // (+) converts string 'id' to a number
							this._crudService.get<Role>('roles', this.roleId).subscribe(
								res => {
									this.role = res;
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

	getRights() {
		this.p1 = this.roleService.moduleRights<Module>('userAccess/roles/modules-rights');
	}

	isChecked(rightId) {
		const index = this.role.rights.findIndex(function (value: any) { return value.right_id === rightId; });
        if (index === -1) {
			return false;
        } else {
			return true;
		}

    }

	updateRights(rightId) {
		const index = this.role.rights.findIndex(function (value: any) { return value.right_id === rightId; });
        if (index === -1) {
            this.role.rights.push({
                'right_id' : rightId
            });
        } else {
			this.role.rights.splice(index);
		}
      }



    redirect() {
        this.router.navigate(['/roles']);
    }

    hasError(roleForm: NgForm, field: string, validation: string) {
		if (roleForm && Object.keys(roleForm.form.controls).length > 0 &&
			roleForm.form.controls[field].errors && validation in roleForm.form.controls[field].errors) {
            if (validation) {
				return (roleForm.form.controls[field].dirty &&
					roleForm.form.controls[field].errors[validation]) || (this.edit && roleForm.form.controls[field].errors[validation]);
            }
			return (roleForm.form.controls[field].dirty &&
				roleForm.form.controls[field].invalid) || (this.edit && roleForm.form.controls[field].invalid);
        }
	}

    save(roleForm: NgForm) {
        this.submitted = true;
        this.edit = true;

        if (roleForm.valid) { // submit form if valid
            if (this.roleId) { // if edit
                this._crudService.edit<Role>('roles', this.role, this.roleId).subscribe(
                    data => {
						this.router.navigate(['/roles']);
                    },
                    error => {
                        this.submitted = false;
                        if ( error.error_code === 3) {
                            this.errors = error.message;
                        }
                    });
            } else { // if add

                this._crudService.add<Role>('roles', this.role).subscribe(
                    data => {
						this.router.navigate(['/roles']);
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
}
