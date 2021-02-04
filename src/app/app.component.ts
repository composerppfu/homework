import { Component } from '@angular/core';
// constant
import { studentList} from './student.const';
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  list = studentList;
}
