use std::sync::atomic::{AtomicUsize, Ordering};
use std::sync::Arc;
use std::thread;
use num_cpus;
use ctrlc::set_handler;
use rustc_version::{version};
use num_format::{Locale, ToFormattedString};
use nix::sched::{CpuSet, sched_setaffinity};
use nix::unistd::Pid;
use core::ptr::write_volatile;


fn main() {
    let count_loops = Arc::new(AtomicUsize::new(0));

    set_handler({
        let count_loops_clone = Arc::clone(&count_loops);
        move || {
            println!("Rust {} looped {} times.",
                     version().unwrap(),
                     count_loops_clone.load(Ordering::SeqCst).to_formatted_string(&Locale::en));
            std::process::exit(0);
        }
    }).expect("Error setting Ctrl-C handler");

    let mut handles = vec![];

    for i in 0..num_cpus::get() {
        let mut cpuset = CpuSet::new();
        cpuset.set(i).expect("Failed to set CPU");

        let count_loops_clone = Arc::clone(&count_loops);
let handle = thread::spawn(move || {
    sched_setaffinity(Pid::from_raw(0), &cpuset).expect("Failed to set affinity");
    let mut x = 0;
    loop {
        for _ in 0..5_000_000 {
            unsafe {
                write_volatile(&mut x, 0);
            }
        }
        count_loops_clone.fetch_add(5_000_000, Ordering::Relaxed);
    }
});
        handles.push(handle);
    }

    for handle in handles {
        handle.join().unwrap();
    }
}