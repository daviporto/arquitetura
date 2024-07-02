import { JobStatus } from "./job-application"

export type PostJobApplicationBody = {
  cover_letter: string
}

export type PatchJobApplicationBody = {
  status: JobStatus
}
