import { map } from 'rxjs/operators';

import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, CanActivateChild, Router, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { RoleService } from '../security/roles.service';


@Injectable()
export class Authorization implements CanActivate , CanActivateChild {
  constructor( private router: Router , private roleService: RoleService) {}

  canActivate( route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean>|Promise<boolean>|boolean {
	const expectedRole = route.data.right;
	  console.log(expectedRole);
	  return true;

  }

	canActivateChild(childRoute: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
		const accessToken = localStorage.getItem('accessToken');
		if (accessToken !== null) {
			if (childRoute.data.right !== undefined && childRoute.data.right * 1 !== 0) {
				return this.roleService.canAccess(childRoute.data.right).pipe(map(res => {
					if (res.canAccess === 1) {
						return true;
					} else {
						this.router.navigate(['/login']);
						return false;
					}
				}));
			} else {
				return true;
			}

		} else {
			this.router.navigate(['/login']);
			// this.router.navigate(['/login'], { queryParams: { returnUrl: state.url } });
			return false;
		}
	}
}
