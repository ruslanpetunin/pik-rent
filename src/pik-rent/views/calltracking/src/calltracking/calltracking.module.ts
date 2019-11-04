import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { CalltrackingComponent } from './calltracking.component';

@NgModule({
  declarations: [
    CalltrackingComponent
  ],
  imports: [
    BrowserModule
  ],
  providers: [],
  bootstrap: [CalltrackingComponent]
})
export class CalltrackingModule { }
