import {isCaptain} from "../../api/crewmates";
import {localStore} from '../../main'

export function canProcess(task) {
  if (task.status !== 'new' || task.status === 'processing') {
    return false;
  }

  if (task.assignee === null && !isCaptain()) {
    return false;
  }

  if (!isCaptain() && task.assignee.id !== localStore.state.user.id) {
    return false;
  }

  return true;
}

export function canFinish(task) {
  if (task.status !== 'processing' || task.status === 'done') {
    return false;
  }

  if (task.assignee === null && !isCaptain()) {
    return false;
  }

  if (!isCaptain() && task.assignee.id !== localStore.state.user.id) {
    return false;
  }

  return true;
}
