# phalcon-queue-worker

Features
---
* Allows the usage of phalcon cli as a RabbitMQ worker (with minor adjustments, others might work as well)

example of usage
---

``` php bin/phalcon workers:run --queue=queue-name``` 
Starts the queue listener

Requirements
---
see composer.json for requirements

Creating a new worker (branching from this project)
---

1. run ```composer create-project cfv1000/phalcon-queue-workers new-project @dev```
2. rename the namespaces
3. create a new JOB in the src/jobs directory (see default job for example)

_Notes_: Don't forget to restart your workers after each change
_Notes #2_: Workers have a big issue with memory consumption. Play it safe!

Q&A
---

Q: Why do you have 2 routers?

A: One router is for CLI commands (like starting a worker, cleaning directories, etc). The other router (jobs) is meant to be used to dispatch the queue jobs.

---

Q: Why do you use "type" property?

A: Convenience. Can be changed to be anything, really. I'd even go as far as suggest transferring serialized objects that implement message interface, and un-serialize in the worker in the WorkerJobDispatcher.

---

Q: "Your code sucks. Where is your taste? Where are the unit-tests?"

A: Well.. be free to write better code than me. I don't mind. This is a proof-of-concept written in 3-4h. I did not have the time to make the code look pretty for you. Use it or don't. Your choice... 

---

Q: Why did you not use events? 

A: Feel free to implement events, if that's what you need. I just chose not to at this moment.

Final thoughts
---
Feel free to credit this repo if it helped. I'd appreciate it :)

Good luck!
