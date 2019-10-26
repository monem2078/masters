import { Injectable } from '@angular/core';
import { ActionNotificationComponent } from '../../content/partials/content/general/action-natification/action-notification.component';
import { MatSnackBar, MatDialog } from '@angular/material';
import { ActionEntityDialogComponent } from '../../content/partials/content/general/modals/action-entity-dialog/action-entity-dialog.component';
import { DeleteEntityDialogComponent } from '../../content/partials/content/general/modals/delete-entity-dialog/delete-entity-dialog.component';

export enum MessageType {
	Create,
	Read,
	Update,
	Delete
}

@Injectable()
export class LayoutUtilsService {
	constructor(private snackBar: MatSnackBar,
		private dialog: MatDialog) { }

	// SnackBar for notifications
	showActionNotification(
		message: string,
		type: MessageType = MessageType.Create,
		duration: number = 10000,
		showCloseButton: boolean = true,
		showUndoButton: boolean = false,
		undoButtonDuration: number = 3000,
		verticalPosition: 'top' | 'bottom' = 'top'
	) {
		return this.snackBar.openFromComponent(ActionNotificationComponent, {
			duration: duration,
			data: {
				message,
				snackBar: this.snackBar,
				showCloseButton: showCloseButton,
				showUndoButton: showUndoButton,
				undoButtonDuration,
				verticalPosition,
				type,
				action: 'Undo'
			},
			verticalPosition: verticalPosition
		});
	}

	// Method returns instance of MatDialog
	deleteElement(title: string = '', description: string = '', waitDesciption: string = '') {
		return this.dialog.open(DeleteEntityDialogComponent, {
			data: { title, description, waitDesciption },
			width: '440px'
		});
	}

	actionElement(title: string = '', description: string = '', waitDesciption: string = '', actionLabel: string = '') {
        return this.dialog.open(ActionEntityDialogComponent, {
            data: {title, description, waitDesciption, actionLabel},
            width: '440px'
        });
    }

}
