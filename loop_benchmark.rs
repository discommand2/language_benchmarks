use std::sync::atomic::{AtomicUsize, Ordering};
use std::sync::Arc;
use std::thread;
use num_cpus;
use ctrlc::set_handler;
use rustc_version::{version};
use num_format::{Locale, ToFormattedString};

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

    for _ in 0..num_cpus::get() {
        let count_loops_clone = Arc::clone(&count_loops);
        let handle = thread::spawn(move || {
            loop {
                for _ in 0..1_000_000 {
                    // This loop will run a million times before moving on
                }
                count_loops_clone.fetch_add(1000000, Ordering::Relaxed);
            }
        });
        handles.push(handle);
    }

    for handle in handles {
        handle.join().unwrap();
    }
}